<?php


namespace BristolSU\Support\Control\Contracts\Repositories;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;

/**
 * Interface GroupTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface GroupTag
{

    public function all();

    /**
     * @param GroupContract $group
     * @return mixed
     */
    public function allThroughGroup(GroupContract $group);

    /**
     * @param $reference
     * @return mixed
     */
    public function getTagByFullReference($reference);
}
