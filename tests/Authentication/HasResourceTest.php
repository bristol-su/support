<?php

namespace BristolSU\Support\Tests\Authentication;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\Authentication\HasResource;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class HasResourceTest extends TestCase
{
    use HasResource;

    /** @test */
    public function activity_instance_id_returns_the_activity_instance_id()
    {
        $activityInstance = ActivityInstance::factory()->create();

        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willReturn(($activityInstance));
        $this->app->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());

        $this->assertEquals($activityInstance->id, static::activityInstanceId());
    }

    /** @test */
    public function module_instance_id_returns_the_module_instance_id()
    {
        $moduleInstance = ModuleInstance::factory()->create();

        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->assertEquals($moduleInstance->id, static::moduleInstanceId());
    }

    /** @test */
    public function for_resource_scope_applies_correct_queries()
    {
        $activityInstance = ActivityInstance::factory()->create();
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willReturn(($activityInstance));
        $this->app->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $builder = $this->prophesize(Builder::class);
        $builder->where('activity_instance_id', $activityInstance->id)->shouldBeCalled()->willReturn($builder->reveal());
        $builder->where('module_instance_id', $moduleInstance->id)->shouldBeCalled();

        $this->scopeForResource($builder->reveal());
    }

    /** @test */
    public function for_resource_can_have_the_ids_overwritten()
    {
        $builder = $this->prophesize(Builder::class);
        $builder->where('activity_instance_id', 100)->shouldBeCalled()->willReturn($builder->reveal());
        $builder->where('module_instance_id', 101)->shouldBeCalled();

        $this->scopeForResource($builder->reveal(), 100, 101);
    }

    /** @test */
    public function for_module_instance_scope_applies_correct_queries()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $builder = $this->prophesize(Builder::class);
        $builder->where('module_instance_id', $moduleInstance->id)->shouldBeCalled();

        $this->scopeForModuleInstance($builder->reveal());
    }

    /** @test */
    public function for_module_instance_can_have_the_ids_overwritten()
    {
        $builder = $this->prophesize(Builder::class);
        $builder->where('module_instance_id', 101)->shouldBeCalled();

        $this->scopeForModuleInstance($builder->reveal(), 101);
    }

    /** @test */
    public function activity_and_module_instance_id_are_set_on_save()
    {
        $activityInstance = ActivityInstance::factory()->create();
        $activityInstanceResolver = $this->prophesize(ActivityInstanceResolver::class);
        $activityInstanceResolver->getActivityInstance()->shouldBeCalled()->willReturn(($activityInstance));
        $this->app->instance(ActivityInstanceResolver::class, $activityInstanceResolver->reveal());
        $moduleInstance = ModuleInstance::factory()->create();
        $this->app->instance(ModuleInstance::class, $moduleInstance);

        $this->getConnection()->getSchemaBuilder()->create('testtable_hasresource', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_instance_id');
            $table->unsignedInteger('module_instance_id');
        });

        $model = FakeModel::create();
        $this->assertEquals($activityInstance->id, $model->activity_instance_id);
        $this->assertEquals($moduleInstance->id, $model->module_instance_id);
    }

    /** @test */
    public function activity_and_module_instance_id_are_not_set_if_given_already()
    {
        $this->getConnection()->getSchemaBuilder()->create('testtable_hasresource', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_instance_id');
            $table->unsignedInteger('module_instance_id');
        });

        $model = FakeModel::create([
            'activity_instance_id' => 500,
            'module_instance_id' => 505
        ]);
        $this->assertEquals(500, $model->activity_instance_id);
        $this->assertEquals(505, $model->module_instance_id);
    }
}

class FakeModel extends Model
{
    use HasResource;

    protected $table = 'testtable_hasresource';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $this->timestamps = false;
        parent::__construct($attributes);
    }
}
