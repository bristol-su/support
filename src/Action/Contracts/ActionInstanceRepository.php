<?php

namespace BristolSU\Support\Action\Contracts;

use Illuminate\Support\Collection;

interface ActionInstanceRepository
{

    /**
     * Get all action instances for a given event and module instance
     * 
     * @param int $moduleInstanceId Module instance the action instances should be attached to
     * @param string $event The event the action instances should respond to
     * 
     * @return Collection
     */
    public function forEvent(int $moduleInstanceId, string $event): Collection;
    
}