<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Tags\PositionTag as PositionTagModel;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory as PositionTagCategoryModel;
use Illuminate\Support\Collection;

/**
 * Interface PositionTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface PositionTagCategory
{

    /**
     * Get all position tag categories
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get the position tag category of a position tag
     *
     * @param PositionTagModel $position
     * @return PositionTagCategoryModel
     */
    public function getThroughTag(PositionTagModel $position): PositionTagCategoryModel;

    /**
     * Get a tag category by the reference
     *
     * @param $reference
     * @return mixed
     */
    public function getByReference(string $reference): PositionTagCategoryModel;

    /**
     * Get a position tag category by id
     *
     * @param int $id
     * @return PositionTagCategoryModel
     */
    public function getById(int $id): PositionTagCategoryModel;
}
