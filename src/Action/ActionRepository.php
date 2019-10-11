<?php


namespace BristolSU\Support\Action;


use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;

class ActionRepository implements ActionRepositoryContract
{
    /**
     * @var ActionManagerContract
     */
    private $manager;

    public function __construct(ActionManagerContract $manager)
    {
        $this->manager = $manager;
    }

    public function all()
    {
        return collect($this->manager->all())->map(function($action) {
            return RegisteredAction::fromArray($action);
        })->values();
    }

    public function fromClass($class)
    {
        $actions = $this->manager->all();
        if(!isset($actions[$class])) {
            throw new \Exception();
        }
        return RegisteredAction::fromArray($actions[$class]);
    }
}
