<?php

namespace BristolSU\Support\Tests\Integration\Module;

use BristolSU\Support\Module\Contracts\Module;
use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Module\Contracts\ModuleManager;
use BristolSU\Support\Module\ModuleFactory;
use BristolSU\Support\Module\ModuleRepository;
use BristolSU\Support\Testing\TestCase;
use Illuminate\Contracts\Container\Container;

class ModuleFactoryTest extends TestCase
{

    /** @test */
    public function fromAlias_resolves_a_module_from_the_container(){
        $module = $this->prophesize(Module::class);
        $app = $this->prophesize(Container::class);
        $app->make(ModuleContract::class, ['alias' => 'alias1'])->shouldBeCalled()->willReturn($module);

        $factory = new ModuleFactory($app->reveal());
        $module = $factory->fromAlias('alias1');
    }

    /** @test */
    public function fromAlias_creates_a_module_instance(){
        $factory = resolve(ModuleFactory::class);
        $module = $factory->fromAlias('alias1');
        
        $this->assertInstanceOf(Module::class, $module);
    }
    
    // TODO Test model created correctly
    
}