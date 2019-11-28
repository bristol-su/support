<?php


namespace BristolSU\Support\Completion;


use BristolSU\Support\Completion\Contracts\CompletionConditionInstanceRepository as CompletionConditionInstanceRepositoryContract;

/**
 * Class CompletionConditionInstanceRepository
 * @package BristolSU\Support\Completion
 */
class CompletionConditionInstanceRepository implements CompletionConditionInstanceRepositoryContract
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = [])
    {
        return CompletionConditionInstance::create($attributes);
    }
    
    public function all()
    {
        return CompletionConditionInstance::all();
    }
}
