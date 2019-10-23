<?php

namespace BristolSU\Support\Control\Contracts\Repositories;

use Illuminate\Support\Collection;

/**
 * Interface Group
 * @package BristolSU\Support\Control\Contracts\Repositories
 */
interface Group
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param \BristolSU\Support\Control\Contracts\Models\GroupTag $tag
     * @return mixed
     */
    public function allWithTag(\BristolSU\Support\Control\Contracts\Models\GroupTag $tag);

    public function all();

    /**
     * @param $id
     * @return Collection
     */
    public function allFromStudentControlID($id): Collection;

}
