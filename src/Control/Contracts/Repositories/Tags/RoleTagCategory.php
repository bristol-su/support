<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\RoleTag as RoleTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTagCategory as RoleTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Interface RoleTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface RoleTagCategory
{

    /**
     * Get all role tag categories
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get the role tag category of a role tag
     *
     * @param RoleTagModel $role
     * @return RoleTagCategoryModel
     */
    public function allThroughTag(RoleTagModel $role): RoleTagCategoryModel;

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): RoleTagCategoryModel;

    /**
     * Get a role tag category by id
     *
     * @param int $id
     * @return RoleTagCategoryModel
     */
    public function getById(int $id): RoleTagCategoryModel;
}
