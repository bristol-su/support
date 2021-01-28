<?php


namespace BristolSU\Support\Progress;


use BristolSU\Support\Progress\Commands\UpdateProgress;
use Illuminate\Support\ServiceProvider;

class ProgressServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('progress-exporter', function($app) {
            return new ProgressManager($app);
        });

        $this->app->bind(ProgressUpdateContract::class, ProgressUpdateRepository::class);
    }

    public function boot()
    {
        $this->commands([
            UpdateProgress::class
        ]);
    }
    
}