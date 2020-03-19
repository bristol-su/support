<?php

namespace BristolSU\Support\Action\Contracts;

use BristolSU\Support\Action\ActionInstance;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    /**
     * Get all action instances for a given module instance
     * 
     * @param int $moduleInstanceId ID of the module instance to get the action instances from
     * @return Collection
     */
    public function forModuleInstance(int $moduleInstanceId): Collection;
    
    /**
     * Get an action instance by ID
     * 
     * @param int $id
     * @return ActionInstance
     * 
     * @throws ModelNotFoundException
     */
    public function getById(int $id): ActionInstance;

    /**
     * Get all action instances registered
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Update an action instance
     * 
     * @param int $id
     * @param array $attributes
     * @return ActionInstance
     * 
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $attributes): ActionInstance;

    /**
     * Delete an action instance
     * 
     * @param int $id
     * @return void
     * 
     * @throws ModelNotFoundException
     */
    public function delete(int $id);
    
}