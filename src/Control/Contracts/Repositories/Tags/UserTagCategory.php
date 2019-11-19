<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\UserTag as UserTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory as UserTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Interface UserTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface UserTagCategory
{

    /**
     * Get all user tag categories
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get the user tag category of a user tag
     *
     * @param UserTagModel $user
     * @return UserTagCategoryModel
     */
    public function allThroughTag(UserTagModel $user): UserTagCategoryModel;

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): UserTagCategoryModel;

    /**
     * Get a user tag category by id
     *
     * @param int $id
     * @return UserTagCategoryModel
     */
    public function getById(int $id): UserTagCategoryModel;
}
