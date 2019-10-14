<?php

namespace BristolSU\Support\Control\Contracts\Repositories;

use Illuminate\Support\Collection;

interface Group
{

    public function getById($id);

    public function allWithTag(\BristolSU\Support\Control\Contracts\Models\GroupTag $tag);

    public function all();

    public function allFromStudentControlID($id): Collection;

}
