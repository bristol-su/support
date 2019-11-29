<?php

namespace BristolSU\Support\Control\Models\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\PositionTag as PositionTagContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionRepository;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTagCategory as PositionTagCategoryRepository;
use Illuminate\Support\Collection;
use BristolSU\Support\Control\Models\Model;

/**
 * Class PositionTag
 * @package BristolSU\Support\Control\Models
 */
class PositionTag extends Model implements PositionTagContract
{
    /**
     * ID of the position tag
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
     * @return PositionTagCategory
     */
    public function category(): PositionTagCategory
    {
        return app(PositionTagCategoryRepository::class)->getById($this->categoryId());
    }

    /**
     * Positions who have this tag
     *
     * @return Collection
     */
    public function positions(): Collection
    {
        return app(PositionRepository::class)->allThroughTag($this);
    }
}

