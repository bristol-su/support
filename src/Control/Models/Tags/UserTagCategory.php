<?php


namespace BristolSU\Support\Control\Models\Tags;


use BristolSU\Support\Control\Contracts\Models\Tags\UserTagCategory as UserTagCategoryModel;
use BristolSU\Support\Control\Contracts\Repositories\Tags\UserTag;
use BristolSU\Support\Control\Models\Model;
use Illuminate\Support\Collection;

class UserTagCategory extends Model implements UserTagCategoryModel
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
        return app(UserTag::class)->allThroughUserTagCategory($this);
    }
}