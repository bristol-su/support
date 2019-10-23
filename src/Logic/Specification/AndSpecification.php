<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Class AndSpecification
 * @package BristolSU\Support\Logic\Specification
 */
class AndSpecification implements Specification
{

    /**
     * @var array
     */
    private $specifications;

    /**
     * AndSpecification constructor.
     * @param mixed ...$specifications
     */
    public function __construct(...$specifications)
    {
        $this->specifications = $specifications;
    }

    /**
     * @return bool
     */
    public function isSatisfied() : bool
    {
        foreach($this->specifications as $specification) {
            if(!$specification->isSatisfied()) {
                return false;
            }
        }

        return true;
    }

}
