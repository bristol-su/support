<?php

namespace BristolSU\Support;

use Illuminate\Support\ServiceProvider;

class ThirdPartyServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register(\Spatie\EloquentSortable\EloquentSortableServiceProvider::class);
    }

    public function boot()
    {
    }

}