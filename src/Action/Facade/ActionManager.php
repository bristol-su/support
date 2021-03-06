<?php

namespace BristolSU\Support\Action\Facade;

use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;
use Illuminate\Support\Facades\Facade;

/**
 * ActionManager.
 *
 * Facade for the action manager, which registers and retrieves actions.
 *
 * @method static void registerAction(string $class, string $name, string $description)
 */
class ActionManager extends Facade
{
    /**
     * Get the key of the ActionManager as registered in the container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ActionManagerContract::class;
    }
}
