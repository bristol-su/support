<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Role as RoleContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTagCategory as RoleTagCategoryContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTag as RoleTagModel;
use Illuminate\Support\Collection;

/**
 * Interface RoleTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface RoleTag
{

    /**
     * Get all role tags
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all role tags which a role is tagged with 
     * 
     * @param RoleContract $role
     * @return Collection
     */
    public function allThroughRole(RoleContract $role): Collection;

    /**
     * Get a tag by the full reference
     * 
     * @param $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): RoleTagModel;
    
    /**
     * Get a role tag by id
     * 
     * @param int $id
     * @return RoleTagModel
     */
    public function getById(int $id): RoleTagModel;

    /**
     * Get all role tags belonging to a role tag category
     *
     * @param RoleTagCategoryContract $roleTagCategory
     * @return Collection
     */
    public function allThroughRoleTagCategory(RoleTagCategoryContract $roleTagCategory): Collection;
}
