<?php


namespace BristolSU\Support\Events\Contracts;


/**
 * Register and retrieve events
 */
interface EventManager
{

    /**
     * Register a new event
     * 
     * @param string $alias Module alias registering the event
     * @param string $name Event Name
     * @param string $class Event Class
     * @param string $description Event Description
     * 
     * @return void
     */
    public function registerEvent($alias, $name, $class, $description);

    /**
     * Get all events
     * 
     * @return array All events
     */
    public function all();

    /**
     * Get all events for a module
     * 
     * @param string $alias Module alias
     * 
     * @return array Events for the module
     */
    public function allForModule($alias);

    
}