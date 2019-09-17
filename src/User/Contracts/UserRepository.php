<?php


namespace BristolSU\Support\User\Contracts;


interface UserRepository
{

    public function getWhereIdentity($identity);

    public function getWhereEmail($email);

    public function create(array $attributes);

    public function all();

}
