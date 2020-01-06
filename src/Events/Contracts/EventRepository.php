<?php


namespace BristolSU\Support\Events\Contracts;


/**
 * Access to events registered by modules
 */
interface EventRepository
{

    /**
     * Get all events a module has registered
     * 
     * Returns events in the form
     * [
     *      'name' => 'Event Name',
     *      'description' => 'Event Description',
     *      'event' => 'EventClassName'
     * ]
     * 
     * @param string $alias Module alias
     * 
     * @return array
     */
    public function allForModule(string $alias);

}
