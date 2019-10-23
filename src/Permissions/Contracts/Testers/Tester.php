<?php


namespace BristolSU\Support\Permissions\Contracts\Testers;


/**
 * Class Tester
 * @package BristolSU\Support\Permissions\Contracts\Testers
 */
abstract class Tester
{

    /**
     * @var Tester
     */
    private $successor = null;

    /**
     * @param Tester|null $tester
     */
    public function setNext(?Tester $tester = null)
    {
        $this->successor = $tester;
    }

    /**
     * @param string $ability
     * @return bool|null
     */
    public function next(string $ability)
    {
        if($this->successor === null) {
            return false;
        }
        return $this->successor->can($ability);
    }

    /**
     * @param string $ability
     * @return bool|null
     */
    abstract public function can(string $ability): ?bool;
}
