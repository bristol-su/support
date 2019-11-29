<?php

namespace BristolSU\Support\Authentication\Contracts;

interface ResourceIdGenerator
{

    public function fromString(string $resourceType): int;
    
}