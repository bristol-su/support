<?php


namespace BristolSU\Support\Completion\Contracts;


interface CompletionEventManager
{

    public function registerEvent($alias, $name, $class, $description);

    public function all();
    
    public function allForModule($alias);

    
}