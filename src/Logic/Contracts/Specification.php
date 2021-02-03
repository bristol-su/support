<?php


namespace BristolSU\Support\Logic\Contracts;

/**
 * ISpecification interface.
 */
interface Specification
{
    /**
     * Is the specification satisfied?
     *
     * @return bool If the specification is satisfied
     */
    public function isSatisfied(): bool;
}
