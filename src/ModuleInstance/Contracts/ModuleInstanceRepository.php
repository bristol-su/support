<?php

namespace BristolSU\Support\ModuleInstance\Contracts;

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

}
