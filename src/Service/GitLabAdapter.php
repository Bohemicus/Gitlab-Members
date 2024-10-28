<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
readonly class GitLabAdapter implements GitAdapterInterface
{
    /**
     * @param HttpClientInterface $client
     * @param string $apiUrl
     * @param string $groupId
     * @param string $token
     * @param string $perPage
     */
    public function __construct(
        private HttpClientInterface $client,
        private string $apiUrl,
        private string $groupId,
        private string $token,
        private string $perPage,
    ) {
    }


    /**
     * Retrieves all child groups and returns the information of the parent group as well
     *
     * @param string|null $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
 */
    public function getParentAndChildGroups(
        string $groupId = null,
        string $page = '1',
        array $options = null
    ): ?iterable {
        $groups = [];
        if (is_null($groupId)) {
            $groupId = $this->groupId;
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/groups/' . $groupId . '?per_page=1&page=1',
                [
                    'auth_bearer' => $this->token]
            );
            $resFatherGroup  = $response->toArray();
            $groups['id'] = $resFatherGroup['id'];
            $groups['name'] = $resFatherGroup['name'];
            $groups['full_path'] = $resFatherGroup['full_path'];
            yield $groups;
        }
        $response = $this->client->request(
            'GET',
            $this->apiUrl . '/groups/' . $groupId . '/descendant_groups?pagination=keyset&per_page=' .
            $this->perPage . '&page=' . $page . '&all_available=true',
            [
                'auth_bearer' => $this->token]
        );
        $headers = $response->getHeaders();
        $nextPage = $headers['x-next-page'][0];

        foreach ($response->toArray() as $group) {
            $groups['id'] = $group['id'];
            $groups['name'] = $group['name'];
            $groups['full_path'] = $group['full_path'];
            yield $groups;
        }
        if ($nextPage != '') {
            yield from  $this->getParentAndChildGroups($groupId, $nextPage);
        }
    }

    /**
     * @param string|null $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getGroupMembers(string $groupId = null, string $page = '1', array $options = null): ?iterable
    {
        $members = [];
        if (is_null($groupId)) {
            $groupId = $this->groupId;
        }
        $response = $this->client->request(
            'GET',
            $this->apiUrl . '/groups/' . $groupId . '/members/all?pagination=keyset&per_page=' .
            $this->perPage . '&page=' . $page,
            [
                'auth_bearer' => $this->token]
        );
        $headers = $response->getHeaders();
        $nextPage = $headers['x-next-page'][0];

        foreach ($response->toArray() as $member) {
            $members['user_id'] = $member['id'];
            $members['name'] = $member['name'] . ' (@' . $member['username'] . ')';
            $members['level'] = $this->getRoleById($member['access_level']);
            $members['group_id'] = $groupId;
            yield $members;
        }
        if ($nextPage != '') {
            yield  from  $this->getGroupMembers($groupId, $nextPage);
        }
    }


    /**
     * @param string $projectId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getProjectMembers(string $projectId, string $page = '1', array $options = null): ?iterable
    {
        $members = [];
        $response = $this->client->request(
            'GET',
            $this->apiUrl . '/projects/' . $projectId . '/members/all?pagination=keyset&per_page=' .
            $this->perPage . '&page=' . $page,
            [
                'auth_bearer' => $this->token]
        );
        $headers = $response->getHeaders();
        $nextPage = $headers['x-next-page'][0];

        foreach ($response->toArray() as $member) {
            $members['name'] = $member['name'] . ' (@' . $member['username'] . ')';
            $members['user_id'] = $member['id'];
            $members['level'] = $this->getRoleById($member['access_level']);
            $members['project_id'] = $projectId;
            yield $members;
        }
        if ($nextPage != '') {
            yield from  $this->getProjectMembers($projectId, $nextPage);
        }
    }

    /**
     * @param string $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getGroupProjects(string $groupId, string $page = '1', array $options = null): ?iterable
    {
        $projects = [];
        $response = $this->client->request(
            'GET',
            $this->apiUrl . '/groups/' . $groupId . '/projects?pagination=keyset&per_page=' .
            $this->perPage . '&page=' . $page . '&include_subgroups=true',
            [
                'auth_bearer' => $this->token]
        );
        $headers = $response->getHeaders();
        $nextPage = $headers['x-next-page'][0];

        foreach ($response->toArray() as $project) {
            $projects['id'] = $project['id'];
            $projects['project'] = $project['name'];
            $projects['full_path'] = $project['path_with_namespace'];
            $projects['kind'] = $project['namespace']['kind'];
            yield $projects;
        }
        if ($nextPage != '') {
            yield from  $this->getGroupProjects($groupId, $nextPage);
        }
    }

    /**
     * Mapping of user roles
     * TODO can be made dynamic, based on the permissions used to connect to gitLab
     *
     * @param $id
     * @return string
     */
    public function getRoleById($id): string
    {
        $roleMap = [
            0 => 'No access',
            5 => 'Minimal access',
            10 => 'Guest',
            20 => 'Reporter',
            30 => 'Developer',
            40 => 'Maintainer',
            50 => 'Owner'
        ];
        if (array_key_exists($id, $roleMap)) {
            return $roleMap[$id];
        } else {
            return 'Not defined';
        }
    }
}
