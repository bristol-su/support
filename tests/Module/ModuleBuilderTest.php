<?php

namespace BristolSU\Support\Tests\Module;

use BristolSU\Support\Events\Contracts\EventRepository;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Completion\Contracts\CompletionCondition;
use BristolSU\Support\Completion\Contracts\CompletionConditionRepository;
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
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $completionRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->module = $this->prophesize(Module::class);
        $this->eventRepository = $this->prophesize(EventRepository::class);
        $this->permissionRepository = $this->prophesize(PermissionRepository::class);
        $this->config = $this->prophesize(Repository::class);
        $this->completionRepository = $this->prophesize(CompletionConditionRepository::class);
        $this->builder = new ModuleBuilder(
            $this->module->reveal(),
            $this->permissionRepository->reveal(),
            $this->config->reveal(),
            $this->eventRepository->reveal(),
            $this->completionRepository->reveal()
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
    public function setCompletionConditions_sets_the_completion_conditions_for_the_module(){
        $this->builder->create('alias1');
        
        $completionCondition1 = $this->prophesize(CompletionCondition::class);
        $completionCondition1->name()->shouldBeCalled()->willReturn('name1');
        $completionCondition1->description()->shouldBeCalled()->willReturn('desc1');
        $completionCondition1->options()->shouldBeCalled()->willReturn(['option1' => 'val1']);
        $completionCondition1->alias()->shouldBeCalled()->willReturn('ccalias1');
        $completionCondition2 = $this->prophesize(CompletionCondition::class);
        $completionCondition2->name()->shouldBeCalled()->willReturn('name2');
        $completionCondition2->description()->shouldBeCalled()->willReturn('desc2');
        $completionCondition2->options()->shouldBeCalled()->willReturn(['option2' => 'val2']);
        $completionCondition2->alias()->shouldBeCalled()->willReturn('ccalias2');
        
        $this->completionRepository->getAllForModule('alias1')->shouldBeCalled()->willReturn([$completionCondition1->reveal(), $completionCondition2->reveal()]);
        $this->module->setCompletionConditions([
            ['name' => 'name1', 'description' => 'desc1', 'options' => ['option1' => 'val1'], 'alias' => 'ccalias1'],
            ['name' => 'name2', 'description' => 'desc2', 'options' => ['option2' => 'val2'], 'alias' => 'ccalias2'],
        ])->shouldBeCalled();
        $this->builder->setCompletionConditions();
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
        
        $builder = new ModuleBuilder($module, $this->permissionRepository->reveal(), $this->config->reveal(), $this->eventRepository->reveal(), $this->completionRepository->reveal());
        $this->assertEquals('alias1', $builder->getModule()->getAlias());
    }
    
    /** @test */
    public function getAlias_returns_the_alias(){
        $module = new \BristolSU\Support\Module\Module();

        $builder = new ModuleBuilderFake($module, $this->permissionRepository->reveal(), $this->config->reveal(), $this->eventRepository->reveal(), $this->completionRepository->reveal());
        $builder->create('alias1');
        $this->assertEquals('alias1', $builder->testGetAlias()); 
    }

    /** @test */
    public function getAlias_throws_an_exception_if_no_alias_set(){
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Set an alias before using the builder');
        $module = new \BristolSU\Support\Module\Module();

        $builder = new ModuleBuilderFake($module, $this->permissionRepository->reveal(), $this->config->reveal(), $this->eventRepository->reveal(), $this->completionRepository->reveal());
        $this->assertEquals('alias1', $builder->testGetAlias());
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

class ModuleBuilderFake extends ModuleBuilder {
    
    public function testGetAlias(): string
    {
        return parent::getAlias(); // TODO: Change the autogenerated stub
    }
    
}