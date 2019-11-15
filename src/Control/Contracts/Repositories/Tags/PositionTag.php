<?php


namespace BristolSU\Support\Control\Contracts\Repositories\Tags;

use BristolSU\Support\Control\Contracts\Models\Position as PositionContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTagCategory as PositionTagCategoryContract;
use BristolSU\Support\Control\Contracts\Models\Tags\PositionTag as PositionTagModel;
use Illuminate\Support\Collection;

/**
 * Interface PositionTag
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface PositionTag
{

    /**
     * Get all position tags
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all position tags which a position is tagged with 
     * 
     * @param PositionContract $position
     * @return Collection
     */
    public function allThroughPosition(PositionContract $position): Collection;

    /**
     * Get a tag by the full reference
     * 
     * @param $reference
     * @return mixed
     */
    public function getTagByFullReference(string $reference): PositionTagModel;
    
    /**
     * Get a position tag by id
     * 
     * @param int $id
     * @return PositionTagModel
     */
    public function getById(int $id): PositionTagModel;

    /**
     * Get all position tags belonging to a position tag category
     *
     * @param PositionTagCategoryContract $positionTagCategory
     * @return Collection
     */
    public function allThroughPositionTagCategory(PositionTagCategoryContract $positionTagCategory): Collection;
}
