<?php

namespace BristolSU\Support\Revision;

use Venturecraft\Revisionable\RevisionableServiceProvider as BaseServiceProvider;

/**
 * Override the default revision service provider
 */
class RevisionServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/revisionable.php' => config_path('revisionable.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../../config/revisionable.php', 'revisionable');
        
        $this->publishes([
            __DIR__ . '/../../migrations/' => database_path('migrations'),
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../../migrations/');


    }

}