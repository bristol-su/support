<?php

namespace BristolSU\Support\ModuleInstance\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface ModuleInstanceRepository
 * @package BristolSU\Support\ModuleInstance\Contracts
 */
interface ModuleInstanceRepository
{
    /**
     * @param int $id
     * @return ModuleInstance
     */
    public function getById(int $id) : ModuleInstance;

    /**
     * @param array $attributes
     * @return ModuleInstance
     */
    public function create(array $attributes) : ModuleInstance;

    /**
     * Get all module instances
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all module instances belonging to a module alias
     * 
     * @param string $alias
     * @return Collection
     */
    public function allWithAlias(string $alias = ''): Collection;
    
}
