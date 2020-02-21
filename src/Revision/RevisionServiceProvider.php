<?php

namespace BristolSU\Support\Revision;

use Venturecraft\Revisionable\RevisionableServiceProvider as BaseServiceProvider;

class RevisionServiceProvider extends BaseServiceProvider
{

    protected $baseDirectory = __DIR__ . '/../../vendor/venturecraft/revisionable';
    
    public function register()
    {
        parent::register();
        $this->mergeConfigFrom($this->baseDirectory . '/src/config/revisionable.php', 'revisionable');
        $this->loadMigrationsFrom($this->baseDirectory . '/src/migrations');
    }
    
}