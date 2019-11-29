<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Logic\Contracts\Specification;

/**
 * Class OrSpecification
 * @package BristolSU\Support\Logic\Specification
 */
class OrSpecification implements Specification
{

    /**
     * @var array
     */
    private $specifications;

    /**
     * OrSpecification constructor.
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
