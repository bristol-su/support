<?php


namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;
use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use Illuminate\Support\Collection;

/**
 * Retrieves actions from the action manager.
 */
class ActionRepository implements ActionRepositoryContract
{
    /**
     * Holds the action manager instance.
     *
     * @var ActionManagerContract
     */
    private $manager;

    /**
     * Initialises the action repository.
     *
     * @param ActionManagerContract $manager Action Manager instance, holding all registered actions.
     */
    public function __construct(ActionManagerContract $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Retrieve all actions.
     *
     * @return Collection<RegisteredAction>
     */
    public function all()
    {
        return collect($this->manager->all())->map(function ($action) {
            return RegisteredAction::fromArray($action);
        })->values();
    }

    /**
     * Retrieve a RegisteredAction class by class name.
     *
     * @param string $class Class of the action
     *
     * @throws \Exception Throws an exception if the action has not been registered
     * @return \BristolSU\Support\Action\Contracts\RegisteredAction
     */
    public function fromClass($class)
    {
        return RegisteredAction::fromArray(
            $this->manager->fromClass($class)
        );
    }
}
