<?php


namespace BristolSU\Support\Action\Contracts;


interface EventManager
{

    public function registerEvent($alias, $name, $class, $description);

    public function all();
    
    public function allForModule($alias);

    
}