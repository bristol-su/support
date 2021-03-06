<?php

namespace BristolSU\Support\Tests\Module;

use BristolSU\Support\Module\Contracts\ModuleBuilder;
use BristolSU\Support\Module\ModuleFactory;
use BristolSU\Support\Tests\TestCase;

class ModuleFactoryTest extends TestCase
{
    /** @test */
    public function from_alias_builds_a_module_from_an_alias()
    {
        $module = new \BristolSU\Support\Module\Module();
        $module->setAlias('alias1');
        
        $builder = $this->prophesize(ModuleBuilder::class);
        $builder->create('alias1')->shouldBeCalled();
        $builder->setAlias()->shouldBeCalled();
        $builder->setName()->shouldBeCalled();
        $builder->setDescription()->shouldBeCalled();
        $builder->setTriggers()->shouldBeCalled();
        $builder->setPermissions()->shouldBeCalled();
        $builder->setSettings()->shouldBeCalled();
        $builder->setCompletionConditions()->shouldBeCalled();
        $builder->setServices()->shouldBeCalled();
        $builder->setFor()->shouldBeCalled();
        $builder->getModule()->shouldBeCalled()->willReturn($module);
        $this->instance(ModuleBuilder::class, $builder->reveal());
        
        $factory = new ModuleFactory();
        $builtModule = $factory->fromAlias('alias1');
        
        $this->assertEquals('alias1', $builtModule->getAlias());
    }
}
