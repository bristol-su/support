<?php

namespace BristolSU\Support\Control\Contracts\Repositories;

use BristolSU\Support\Control\Contracts\Models\User as UserModel;
use BristolSU\Support\Control\Contracts\Models\Group as GroupModel;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagModel;
use Illuminate\Support\Collection;

/**
 * Interface Group
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface Group
{

    /**
     * Get a group by ID
     *
     * @param $id
     * @return GroupModel
     */
    public function getById(int $id): GroupModel;

    /**
     * Get all groups with a specific tag
     *
     * @param GroupTagModel $groupTag
     * @return Collection
     */
    public function allThroughTag(GroupTagModel $groupTag): Collection;

    /**
     * Get all groups
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all groups the given user is a member of
     * 
     * @param $id
     * @return Collection
     */
    public function allThroughUser(UserModel $user): Collection;

}
