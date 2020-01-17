<?php


namespace BristolSU\Support\Action;


use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use BristolSU\Support\Action\Contracts\ActionInstanceRepository as ActionInstanceRepositoryContract;
use BristolSU\Support\Action\Contracts\ActionManager as ActionManagerContract;
use BristolSU\Support\Action\Contracts\ActionRepository as ActionRepositoryContract;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Action\Facade\ActionManager as ActionManagerFacade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Action Service Provider
 */
class ActionServiceProvider extends ServiceProvider
{

    /**
     * Register
     * 
     * - Bind the action repository contract
     * - Bind the action manager contract
     * - Bind the action builder contract
     */
    public function register()
    {
        // Bind interfaces to contracts
        $this->app->bind(ActionRepositoryContract::class, ActionRepository::class);
        $this->app->singleton(ActionManagerContract::class, ActionManager::class);
        $this->app->bind(ActionBuilderContract::class, ActionBuilder::class);
        $this->app->bind(ActionInstanceRepositoryContract::class, ActionInstanceRepository::class);
    }

    /**
     * Boot
     * 
     * - Initialise the Action Dispatcher to trigger on events
     * - Register default actions
     */
    public function boot()
    {
        Event::listen(TriggerableEvent::class, ActionDispatcher::class);
        
        ActionManagerFacade::registerAction(Log::class, 'Log', 'Log some text');
    }
    
}
