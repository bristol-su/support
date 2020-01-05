<?php


namespace BristolSU\Support\Events\Contracts;


/**
 * Interface EventRepository
 */
interface EventRepository
{

    /**
     * @param string $alias
     * @return mixed
     */
    public function allForModule(string $alias);

}
