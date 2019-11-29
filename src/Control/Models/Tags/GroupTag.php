<?php

namespace BristolSU\Support\Control\Models\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\GroupTag as GroupTagContract;
use BristolSU\Support\Control\Contracts\Models\Tags\GroupTagCategory;
use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Tags\GroupTagCategory as GroupTagCategoryRepository;
use BristolSU\Support\Control\Models\Model;
use Illuminate\Support\Collection;

/**
 * Class GroupTag
 * @package BristolSU\Support\Control\Models
 */
class GroupTag extends Model implements GroupTagContract
{
    /**
     * ID of the group tag
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
        return $this->attributes['category']['reference'].'.'.$this->attributes['reference'];
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
     * @return GroupTagCategory
     */
    public function category(): GroupTagCategory
    {
        return app(GroupTagCategoryRepository::class)->getById($this->categoryId());
    }

    /**
     * Groups who have this tag
     *
     * @return Collection
     */
    public function groups(): Collection
    {
        return app(GroupRepository::class)->allThroughTag($this);
    }
}

