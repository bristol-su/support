<?php

namespace BristolSU\Support\Revision;

use Venturecraft\Revisionable\RevisionableServiceProvider as BaseServiceProvider;

/**
 * Override the default revision service provider
 */
class RevisionServiceProvider extends BaseServiceProvider
{

    /**
     * Merge the config and migrations
     * 
     * The VentureCraft package has not merged config or loaded migrations, therefore the SDK is unable to use them
     * by default. To get around this, we merge it here, using the base directory from the 'publishes' array.
     */
    public function boot()
    {
        parent::boot();
        $this->mergeConfig();
        $this->loadMigrations();
    }

    protected function mergeConfig()
    {
        if(array_key_exists(static::class, static::$publishes)) {
            foreach(static::$publishes[static::class] as $key => $value) {
                if(strpos($key, 'config/revisionable.php') !== false && file_exists($key)) {
                    $this->mergeConfigFrom($key, 'revisionable');
                }
            }
        }
    }

    protected function loadMigrations()
    {
        if(array_key_exists(static::class, static::$publishes)) {
            foreach(static::$publishes[static::class] as $key => $value) {
                if(strpos($key, 'migrations') !== false && is_dir($key)) {
                    $this->loadMigrationsFrom($key);
                }
            }
        }
    }

}