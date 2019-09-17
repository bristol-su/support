<?php


namespace BristolSU\Support\Logic\Contracts;


interface LogicRepository
{

    public function create(array $attributes);

    public function all();
}
