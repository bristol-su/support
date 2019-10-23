<?php


namespace BristolSU\Support\Action\Contracts\Events;


/**
 * Interface EventRepository
 * @package BristolSU\Support\Action\Contracts\Events
 */
interface EventRepository
{

    /**
     * @param string $alias
     * @return mixed
     */
    public function allForModule(string $alias);

}
