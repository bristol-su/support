<?php

namespace BristolSU\Support\Control\Contracts\Repositories;

interface Group
{

    public function getById($id);

    public function allWithTag(\BristolSU\Support\Control\Contracts\Models\GroupTag $tag);

    public function all();
}
