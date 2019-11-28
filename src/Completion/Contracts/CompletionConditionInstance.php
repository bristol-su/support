<?php


namespace BristolSU\Support\Completion\Contracts;


/**
 * Interface CompletionConditionInstance
 * @package BristolSU\Support\Completion\Contracts
 */
interface CompletionConditionInstance
{
    public function name();

    public function alias();

    public function settings();
}
