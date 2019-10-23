<?php


namespace BristolSU\Support\Action;


use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;
use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use BristolSU\Support\Action\Contracts\Events\EventManager as EventManagerContract;
use BristolSU\Support\Action\Contracts\Events\EventRepository as EventRepositoryContract;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Action\Events\EventManager;
use BristolSU\Support\Action\Events\EventRepository;
use BristolSU\Support\Action\Facade\ActionManager as ActionManagerFacade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Class ActionServiceProvider
 * @package BristolSU\Support\Action
 */
class ActionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(ActionRepositoryContract::class, ActionRepository::class);
        $this->app->singleton(ActionManagerContract::class, ActionManager::class);
        $this->app->bind(ActionBuilderContract::class, ActionBuilder::class);
        $this->app->bind(EventRepositoryContract::class, EventRepository::class);
        $this->app->singleton(EventManagerContract::class, function($app) {
            return new EventManager;
        });
    }

    public function boot()
    {
        ActionManagerFacade::registerAction(Log::class, 'Log', 'Log some text');
        Event::listen(TriggerableEvent::class, ActionDispatcher::class);
    }
    
}
