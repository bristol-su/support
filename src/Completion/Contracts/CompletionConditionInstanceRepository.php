<?php


namespace BristolSU\Support\Completion\Contracts;


/**
 * Interface CompletionConditionInstanceRepository
 * @package BristolSU\Support\Completion\Contracts
 */
interface CompletionConditionInstanceRepository
{

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    public function all();
}
