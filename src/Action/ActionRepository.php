<?php


namespace BristolSU\Support\Action;


use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;

/**
 * Class ActionRepository
 * @package BristolSU\Support\Action
 */
class ActionRepository implements ActionRepositoryContract
{
    /**
     * @var ActionManagerContract
     */
    private $manager;

    /**
     * ActionRepository constructor.
     * @param ActionManagerContract $manager
     */
    public function __construct(ActionManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return collect($this->manager->all())->map(function($action) {
            return RegisteredAction::fromArray($action);
        })->values();
    }

    /**
     * @param $class
     * @return RegisteredAction|mixed
     */
    public function fromClass($class)
    {
        return RegisteredAction::fromArray(
            $this->manager->fromClass($class)
        );
    }
}
