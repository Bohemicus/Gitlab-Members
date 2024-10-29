<?php

namespace App\Model;

use App\Service\GitAdapterInterface;

/**
 * Model for services
 */
class GitRepository
{
    /**
     * @param GitAdapterInterface $repository
     */
    public function __construct(
        private readonly GitAdapterInterface $repository,
    ) {
    }

    /**
     * Data to be displayed are retrieved and formatted
     * TODO: do a check on using php array features for better and faster performance
     *
     * @return array
     */
    public function getUsersInfoFormatted(): array
    {
        $users = [];
        $allGroups = $this->repository->getParentAndChildGroups();
        foreach ($allGroups as $group) {
            foreach ($this->repository->getGroupMembers($group['id']) as $members) {
                if (array_key_exists($members['user_id'], $users)) {
                    if ($users[$members['user_id']]['Groups'] == 'No Group') {
                        $users[$members['user_id']]['Groups']  =
                        $group['full_path'] . ' (' . $members['level'] . ')';
                    } else {
                        $users[$members['user_id']]['Groups']  =
                        $users[$members['user_id']]['Groups'] . ', ' .
                        $group['full_path'] . ' (' . $members['level'] . ')';
                    }
                } else {
                    $users[$members['user_id']] = [
                    'Name' => $members['name'],
                    'Groups' => $group['full_path'] . ' (' . $members['level'] . ')',
                    'Projects' => 'No Project'
                    ];
                }
            }

            foreach ($this->repository->getGroupProjects($group['id']) as $project) {
                foreach ($this->repository->getProjectMembers($project['id']) as $projectMembers) {
                    if (array_key_exists($projectMembers['user_id'], $users)) {
                        if ($users[$projectMembers['user_id']]['Projects'] == 'No Project') {
                            $users[$projectMembers['user_id']]['Projects']  =
                                $project['full_path'] . ' (' . $projectMembers['level'] . ')';
                        } else {
                            $users[$projectMembers['user_id']]['Projects']  =
                                $users[$projectMembers['user_id']]['Projects'] . ', ' .
                                $project['full_path'] . ' (' . $projectMembers['level'] . ')';
                        }
                    } else {
                        $users[$projectMembers['user_id']] = [
                            'Name' => $projectMembers['name'],
                            'Groups' => 'No Group',
                            'Projects' => $project['full_path'] . ' (' . $projectMembers['level'] . ')'
                        ];
                    }
                }
            }
        }
        return $users;
    }
}
