<?php


namespace BristolSU\Support\Action\Contracts\Events;


interface EventRepository
{

    public function allForModule(string $alias);

}
