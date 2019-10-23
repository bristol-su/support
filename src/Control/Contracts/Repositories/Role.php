<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use Illuminate\Support\Collection;

/**
 * Interface Role
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface Role
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $id
     * @return Collection
     */
    public function allFromStudentControlID($id): Collection;

    /**
     * @return Collection
     */
    public function all(): Collection;
}
