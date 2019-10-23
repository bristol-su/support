<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use Illuminate\Support\Collection;

/**
 * Interface Position
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface Position
{
    /**
     * @return Collection
     */
    public function all(): Collection;
}
