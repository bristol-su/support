<?php

namespace BristolSU\Support\Tests\Module;

use BristolSU\Support\Action\Contracts\Events\EventRepository;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Module\Contracts\Module;
use BristolSU\Support\Module\ModuleBuilder;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Config\Repository;

class ModuleBuilderTest extends TestCase
{

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $module;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $permissionRepository;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $config;
    /**
     * @var ModuleBuilder
     */
    private $builder;
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $eventRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->module = $this->prophesize(Module::class);
        $this->eventRepository = $this->prophesize(EventRepository::class);
        $this->permissionRepository = $this->prophesize(PermissionRepository::class);
        $this->config = $this->prophesize(Repository::class);
        $this->builder = new ModuleBuilder(
            $this->module->reveal(),
            $this->permissionRepository->reveal(),
            $this->config->reveal(),
            $this->eventRepository->reveal()
        );
    }

    /** @test */
    public function setAlias_sets_the_alias_in_the_module(){
        $this->builder->create('alias1');
        $this->module->setAlias('alias1')->shouldBeCalled();
        $this->builder->setAlias();
    }

    /** @test */
    public function setName_sets_the_name_in_the_module(){
        $this->builder->create('alias1');
        $this->config->get('alias1.name')->shouldBeCalled()->willReturn('name1');
        $this->module->setName('name1')->shouldBeCalled();
        $this->builder->setName();
    }

    /** @test */
    public function setDescription_sets_the_description_in_the_module(){
        $this->builder->create('alias1');
        $this->config->get('alias1.description')->shouldBeCalled()->willReturn('description1');
        $this->module->setDescription('description1')->shouldBeCalled();
        $this->builder->setDescription();
    }

    /** @test */
    public function setSettings_sets_the_settings_in_the_module(){
        $this->builder->create('alias1');
        $this->config->get('alias1.settings')->shouldBeCalled()->willReturn(['settings1']);
        $this->module->setSettings(['settings1'])->shouldBeCalled();
        $this->builder->setSettings();
    }

    /** @test */
    public function setPermissions_sets_the_permissions_in_the_module(){
        $this->builder->create('alias1');
        $this->permissionRepository->forModule('alias1')->shouldBeCalled()->willReturn(['permissions1']);
        $this->module->setPermissions(['permissions1'])->shouldBeCalled();
        $this->builder->setPermissions();
    }

    /** @test */
    public function setTriggers_sets_the_triggers_for_the_module(){
        $this->builder->create('alias1');
        $this->eventRepository->allForModule('alias1')->shouldBeCalled()->willReturn([['event' => Trigger::class]]);
        $this->module->setTriggers([['event' => Trigger::class]])->shouldBeCalled();
        $this->builder->setTriggers();
    }   
    
    /** @test */
    public function setTriggers_only_sets_events_implementing_triggerable_event(){
        $this->builder->create('alias1');
        $this->eventRepository->allForModule('alias1')->shouldBeCalled()->willReturn([['event' => Trigger::class], ['event' => self::class]]);
        $this->module->setTriggers([['event' => Trigger::class]])->shouldBeCalled();
        $this->builder->setTriggers();
    }
    
    /** @test */
    public function getModule_returns_the_built_module(){
        $module = new \BristolSU\Support\Module\Module();
        $module->setAlias('alias1');
        
        $builder = new ModuleBuilder($module, $this->permissionRepository->reveal(), $this->config->reveal(), $this->eventRepository->reveal());
        $this->assertEquals('alias1', $builder->getModule()->getAlias());
    }
    
}

class Trigger implements TriggerableEvent
{

    public function getFields(): array
    {
        return [];
    }

    public static function getFieldMetaData(): array
    {
        return [];
    }
}