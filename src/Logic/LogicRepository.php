<?php


namespace BristolSU\Support\Logic;


use BristolSU\Support\Logic\Contracts\LogicRepository as LogicRepositoryContract;

class LogicRepository implements LogicRepositoryContract
{

    public function create(array $attributes)
    {
        return Logic::create($attributes);
    }

    public function all()
    {
        return Logic::all();
    }
}
