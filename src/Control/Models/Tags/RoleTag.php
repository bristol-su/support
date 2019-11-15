<?php

namespace BristolSU\Support\Control\Models\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\RoleTag as RoleTagContract;
use BristolSU\Support\Control\Contracts\Models\Tags\RoleTagCategory;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\Tags\RoleTagCategory as RoleTagCategoryRepository;
use Illuminate\Support\Collection;
use BristolSU\Support\Control\Models\Model;

/**
 * Class RoleTag
 * @package BristolSU\Support\Control\Models
 */
class RoleTag extends Model implements RoleTagContract
{
    /**
     * ID of the role tag
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
     * @return RoleTagCategory
     */
    public function category(): RoleTagCategory
    {
        return app(RoleTagCategoryRepository::class)->getById($this->categoryId());
    }

    /**
     * Roles who have this tag
     *
     * @return Collection
     */
    public function roles(): Collection
    {
        return app(RoleRepository::class)->allThroughTag($this);
    }
}

