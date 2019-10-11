<?php

namespace BristolSU\Support\Action\Contracts;

interface ActionManager
{

    public function registerAction($class, $name, $description);

    public function all();
    
}