<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * TODO to be moved to tests and implement everything
 * the constructor can possibly receive another “RAW”
 * gitHub interface so that the real methods and
 * terminologies can be adapted e.g. teams-> groups etc...
 */
readonly class GitHubAdapter implements GitAdapterInterface
{
    public function __construct(
        // TODO implement me
        // Inject eventually gitHub inerface (if necessary)
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
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getParentAndChildGroups(
        string $groupId = null,
        string $page = '1',
        array $options = null
    ): ?iterable {
        yield
        [
            'id' => 'None',
            'user_id' => 'None',
            'name' => 'None',
            'full_path' => 'None',
        ]
        ; // TODO implement me
    }

    /**
     * @param string|null $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getGroupMembers(string $groupId = null, string $page = '1', array $options = null): ?iterable
    {
        yield
            [
                'id' => 'None',
                'user_id' => 'None',
                'name' => 'None',
                'full_path' => 'None',
                'level' => 'None'
            ]

        ; // TODO implement me
    }


    /**
     * @param string $projectId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getProjectMembers(string $projectId, string $page = '1', array $options = null): ?iterable
    {
        yield
        [
            'id' => 'None',
            'user_id' => 'None',
            'name' => 'None',
            'full_path' => 'None',
            'level' => 'None'
        ]
        ; // TODO implement me
    }

    /**
     * @param string $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getGroupProjects(string $groupId, string $page = '1', array $options = null): ?iterable
    {
        yield
            [
                'id' => 'None',
                'name' => 'None',
                'full_path' => 'None',
           ]
        ; // TODO implement me
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
             // TODO implement me
        ];
            return 'Not defined';
    }
}
