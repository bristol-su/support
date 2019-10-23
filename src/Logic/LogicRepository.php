<?php


namespace BristolSU\Support\Logic;


use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;

/**
 * Class LogicRepository
 * @package BristolSU\Support\Logic
 */
class LogicRepository implements LogicRepositoryContract
{

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return Logic::create($attributes);
    }

    /**
     * @return Logic[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Logic::all();
    }
}
