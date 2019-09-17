<?php


namespace BristolSU\Support\Logic\Specification;


use BristolSU\Support\Logic\Contracts\Specification;

class AndSpecification implements Specification
{

    /**
     * @var array
     */
    private $specifications;

    public function __construct(...$specifications)
    {
        $this->specifications = $specifications;
    }

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
