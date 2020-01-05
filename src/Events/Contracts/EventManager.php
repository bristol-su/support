<?php


namespace BristolSU\Support\Events\Contracts;


/**
 * Interface EventManager
 */
interface EventManager
{

    /**
     * @param $alias
     * @param $name
     * @param $class
     * @param $description
     * @return mixed
     */
    public function registerEvent($alias, $name, $class, $description);

    public function all();

    /**
     * @param $alias
     * @return mixed
     */
    public function allForModule($alias);

    
}