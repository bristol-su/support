<?php


namespace BristolSU\Support\Completion\Contracts;


interface CompletionEventRepository
{

    public function allForModule(string $alias);

}
