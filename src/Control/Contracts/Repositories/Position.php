<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use Illuminate\Support\Collection;

interface Position
{
    public function all(): Collection;
}
