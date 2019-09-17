<?php


namespace BristolSU\Support\Control\Contracts\Repositories;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;

interface GroupTag
{

    public function all();

    public function allThroughGroup(GroupContract $group);

    public function getTagByFullReference($reference);
}
