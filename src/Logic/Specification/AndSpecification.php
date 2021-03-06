<?php


namespace BristolSU\Support\Logic\Specification;

use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Are all the given specifications satisfied?
 */
class AndSpecification implements Specification
{
    /**
     * Holds the specifications.
     *
     * @var array
     */
    private $specifications;

    /**
     * @param Specification ...$specifications Specifications to test
     */
    public function __construct(...$specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * Are all the given specifications satisfied?
     *
     * @return bool If all the specifications are satisfied
     */
    public function isSatisfied(): bool
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfied()) {
                return false;
            }
        }

        return true;
    }
}
