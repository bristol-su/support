<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Group as GroupContract;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTagCategory as GroupTagCategoryContract;
use Illuminate\Support\Collection;

/**
 * Interface GroupTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface GroupTag
{

    /**
     * Get all group tags
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all group tags which a group is tagged with 
     * 
     * @param GroupContract $group
     * @return Collection
     */
    public function allThroughGroup(GroupContract $group): Collection;

    /**
     * Get a tag by the full reference
     * 
     * @param string $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): GroupTagModel;
    
    /**
     * Get a group tag by id
     * 
     * @param int $id
     * @return GroupTagModel
     */
    public function getById(int $id): GroupTagModel;

    /**
     * Get all group tags belonging to a group tag category
     * 
     * @param GroupTagCategoryContract $groupTagCategory
     * @return Collection
     */
    public function allThroughGroupTagCategory(GroupTagCategoryContract $groupTagCategory): Collection;
}
