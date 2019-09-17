<?php

namespace BristolSU\Support\Module;

use BristolSU\Support\Module\Contracts\Module;
use BristolSU\Support\Module\Contracts\ModuleFactory as ModuleFactoryAlias;
use Illuminate\Contracts\Container\Container;

class ModuleFactory implements ModuleFactoryAlias
{

    /**
     * @var Container
     */
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }
    
    public function fromAlias(string $alias): Module
    {
        return $this->app->make(Module::class, ['alias' => $alias]);
    }
}