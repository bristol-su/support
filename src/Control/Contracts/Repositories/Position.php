<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use BristolSU\Support\Control\Contracts\Models\Position as PositionModel;
use Illuminate\Support\Collection;

/**
 * Interface Position
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface Position
{
    /**
     * Get all positions
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get a position by a given ID
     * 
     * @param int $id
     * @return PositionModel
     */
    public function getById(int $id): PositionModel;
}
