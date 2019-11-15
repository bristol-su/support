<?php

namespace BristolSU\Support\Control\Models\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\UserTag as UserTagContract;
use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTagCategory as UserTagCategoryRepository;
use Illuminate\Support\Collection;
use BristolSU\Support\Control\Models\Model;

/**
 * Class UserTag
 * @package BristolSU\Support\Control\Models
 */
class UserTag extends Model implements UserTagContract
{
    /**
     * ID of the user tag
     *
     * @return int
     */
    public function id(): int
    {
        return $this->attributes['id'];
    }

    /**
     * Name of the tag
     *
     * @return string
     */
    public function name(): string
    {
        return $this->attributes['name'];
    }

    /**
     * Full reference of the tag
     *
     * This should be the tag category reference and the tag reference, separated with a period.
     * @return string
     */
    public function fullReference(): string
    {
        return $this->attributes['category']['reference'] . '.' . $this->attributes['reference'];
    }

    /**
     * Description of the tag
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Reference of the tag
     *
     * @return string
     */
    public function reference(): string
    {
        return $this->reference;
    }

    /**
     * ID of the tag category
     * @return int
     */
    public function categoryId(): int
    {
        return ($this->category)['id'];
    }

    /**
     * Tag Category
     *
     * @return UserTagCategory
     */
    public function category(): UserTagCategory
    {
        return app(UserTagCategoryRepository::class)->getById($this->categoryId());
    }

    /**
     * Users who have this tag
     *
     * @return Collection
     */
    public function users(): Collection
    {
        return app(UserRepository::class)->allThroughTag($this);
    }
}

