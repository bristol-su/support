<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory as UserTagCategoryContract;
use BristolSU\Support\Control\Contracts\Models\User as UserContract;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTag as UserTagModel;
use Illuminate\Support\Collection;

/**
 * Interface UserTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface UserTag
{

    /**
     * Get all user tags
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all user tags which a user is tagged with 
     * 
     * @param UserContract $user
     * @return Collection
     */
    public function allThroughUser(UserContract $user): Collection;

    /**
     * Get a tag by the full reference
     * 
     * @param $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): UserTagModel;
    
    /**
     * Get a user tag by id
     * 
     * @param int $id
     * @return UserTagModel
     */
    public function getById(int $id): UserTagModel;

    /**
     * Get all user tags belonging to a user tag category
     *
     * @param UserTagCategoryContract $userTagCategory
     * @return Collection
     */
    public function allThroughUserTagCategory(UserTagCategoryContract $userTagCategory): Collection;
}
