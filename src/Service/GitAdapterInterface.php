<?php

namespace App\Service;

/**
 *
 */
interface GitAdapterInterface
{
    /**
     * Retrieves all child groups and returns the information of the parent group as well
     *
     * @param string|null $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     */
    public function getParentAndChildGroups(
        string $groupId = null,
        string $page = '1',
        array $options = null
    ): ?iterable;

    /**
     * Retrieves all members of a project
     *
     * @param string $projectId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     */

    public function getProjectMembers(string $projectId, string $page = '1', array $options = null): ?iterable;

    /**
     * Retrieves all members of a group
     *
     * @param string|null $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     */

    public function getGroupMembers(string $groupId = null, string $page = '1', array $options = null): ?iterable;

    /**
     * Retrieves all projects of a group
     *
     * @param string $groupId
     * @param string $page
     * @param array|null $options
     * @return iterable|null
     */
    public function getGroupProjects(string $groupId, string $page = '1', array $options = null): ?iterable;
}
