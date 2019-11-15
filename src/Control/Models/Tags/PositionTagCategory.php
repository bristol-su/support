<?php


namespace BristolSU\Support\Control\Models\Tags;


use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory as PositionTagCategoryModel;
use BristolSU\Support\Control\Contracts\Repositories\Tags\PositionTag;
use BristolSU\Support\Control\Models\Model;
use Illuminate\Support\Collection;

class PositionTagCategory extends Model implements PositionTagCategoryModel
{

    /**
     * ID of the tag category
     *
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Name of the tag category
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Deacription of the tag category
     *
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * Reference of the tag category
     *
     * @return string
     */
    public function reference(): string
    {
        return $this->reference;
    }

    /**
     * All tags under this tag category
     *
     * @return Collection
     */
    public function tags(): Collection
    {
        return app(PositionTag::class)->allThroughPositionTagCategory($this);
    }
}