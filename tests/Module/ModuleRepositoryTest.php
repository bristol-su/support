<?php


namespace BristolSU\Support\Tests\Module\Module;


use BristolSU\Support\Module\Contracts\Module as ModuleContract;
use BristolSU\Support\Module\Contracts\ModuleFactory;
use BristolSU\Support\Module\Contracts\ModuleManager;
use BristolSU\Support\Module\Module;
use BristolSU\Support\Module\ModuleRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;
use BristolSU\Support\Testing\TestCase;

class ModuleRepositoryTest extends TestCase
{
    
    

    /** @test */
    public function it_gets_a_module_by_alias(){
        $module = $this->prophesize(ModuleContract::class);
        $manager = $this->prophesize(ModuleManager::class);
        $factory = $this->prophesize(ModuleFactory::class);
        
        $manager->exists('alias1')->shouldBeCalled()->willReturn(true);
        $factory->fromAlias('alias1')->shouldBeCalled()->willReturn($module->reveal());
        
        $moduleRepository = new ModuleRepository($manager->reveal(), $factory->reveal());

        $createdModule = $moduleRepository->findByAlias('alias1');
        $this->assertEquals($module->reveal(), $createdModule);
        
    }

    /** @test */
    public function it_returns_null_if_module_not_found(){
        $module = $this->prophesize(ModuleContract::class);
        $manager = $this->prophesize(ModuleManager::class);
        $factory = $this->prophesize(ModuleFactory::class);

        $manager->exists('alias1')->shouldBeCalled()->willReturn(false);

        $moduleRepository = new ModuleRepository($manager->reveal(), $factory->reveal());

        $this->assertNull(
            $moduleRepository->findByAlias('alias1')
        );
    }

    /** @test */
    public function it_gets_all_modules(){
        $manager = new \BristolSU\Support\Module\ModuleManager;
        $manager->register('alias1');
        $manager->register('alias2');

        $module1 = $this->prophesize(ModuleContract::class);
        $module2 = $this->prophesize(ModuleContract::class);

        $factory = $this->prophesize(ModuleFactory::class);
        $factory->fromAlias('alias1')->shouldBeCalled()->willReturn($module1->reveal());
        $factory->fromAlias('alias2')->shouldBeCalled()->willReturn($module2->reveal());

        $moduleRepository = new ModuleRepository($manager, $factory->reveal());

        $this->assertEquals([
            'alias1' => $module1->reveal(),
            'alias2' => $module2->reveal()
        ], $moduleRepository->all());
    }

}
