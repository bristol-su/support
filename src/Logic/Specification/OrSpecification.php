<?php


namespace BristolSU\Support\Logic\Specification;

use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Are any of the given specifications satisfied?
 */
class OrSpecification implements Specification
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
     * Are any of the given specifications satisfied?
     *
     * @return bool If any of the specifications are satisfied
     */
    public function isSatisfied(): bool
    {
        if (count($this->specifications) === 0) {
            return true;
        }
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfied()) {
                return true;
            }
        }

        return false;
    }
}
