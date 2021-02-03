<?php

namespace BristolSU\Support\Authentication\Contracts;

/**
 * Generates a resource ID from a resource type.
 */
interface ResourceIdGenerator
{
    /**
     * Return the resource ID from a resource type.
     *
     * If the resourceType is user, it will return the user id. It is up to the implementation as to where
     * the resources are found.
     *
     * @param string $resourceType User, Group or Role
     * @return int ID of the resource
     */
    public function fromString(string $resourceType): int;
}
