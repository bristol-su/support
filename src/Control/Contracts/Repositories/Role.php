<?php


namespace BristolSU\Support\Control\Contracts\Repositories;


use Illuminate\Support\Collection;

interface Role
{

    public function getById($id);

    public function allFromStudentControlID($id): Collection;

    public function all(): Collection;
}
