<?php

namespace BristolSU\Support\Action\Facade;

use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class ActionManager
 * 
 * @method static void registerAction($class, $name, $description)
 */
class ActionManager extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ActionManagerContract::class;
    }

}