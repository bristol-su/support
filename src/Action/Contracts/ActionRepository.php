<?php

namespace BristolSU\Support\Action\Contracts;

interface ActionRepository
{

    public function all();

    public function fromClass($class);

}
