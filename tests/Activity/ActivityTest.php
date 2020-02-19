<?php


namespace BristolSU\Support\Tests\Activity;


use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Carbon\Carbon;
use BristolSU\Support\Tests\TestCase;

class ActivityTest extends TestCase
{

    /**
     * @test
     */
    public function it_has_many_module_instances()
    {
        $activity = factory(Activity::class)->create();
        $moduleInstances = factory(ModuleInstance::class, 10)->make();
        $activity->moduleInstances()->saveMany($moduleInstances);

        for ($i = 0; $i < $moduleInstances->count(); $i++) {

            $this->assertTrue($moduleInstances[$i]->is(
                $activity->moduleInstances[$i])
            );
        }
    }

    /**
     * @test
     */
    public function active_retrieves_always_active_events()
    {
        $activity = factory(Activity::class)->create([
            'start_date' => null, 'end_date' => null
        ]);
        $retrieved = Activity::active()->get();

        $this->assertModelEquals($activity, $retrieved->first());
    }

    /**
     * @test
     */
    public function active_retrieves_an_active_activity_in_a_date_range()
    {
        $activity = factory(Activity::class)->create([
            'start_date' => Carbon::now()->subDays(5), 'end_date' => Carbon::now()->addDays(5)
        ]);

        $retrieved = Activity::active()->get();

        $this->assertModelEquals($activity, $retrieved->first());
    }

    /**
     * @test
     */
    public function active_does_not_retrieve_an_activity_if_the_activity_is_not_in_the_date_range()
    {
        $activity = factory(Activity::class)->create([
            'start_date' => Carbon::now()->subDays(5), 'end_date' => Carbon::now()->subdays(1)
        ]);
        $retrieved = Activity::active()->get();

        $this->assertCount(0, $retrieved);
    }

    /** @test */
    public function enabled_does_not_retrieve_an_activity_if_it_is_not_enabled(){
        $activity = factory(Activity::class)->create(['enabled' => false]);
        
        $retrieved = Activity::enabled()->get();

        $this->assertCount(0, $retrieved);
    }

    /** @test */
    public function enabled_retrieves_an_activity_if_it_is_enabled(){
        $activity = factory(Activity::class)->create(['enabled' => true]);

        $retrieved = Activity::enabled()->get();

        $this->assertCount(1, $retrieved);
        $this->assertModelEquals($activity, $retrieved->first());
    }

    
    /** @test */
    public function it_has_a_for_logic(){
        $activity = factory(Activity::class)->create();
        $this->assertInstanceOf(Logic::class, $activity->forLogic);
        $this->assertEquals($activity->for_logic, $activity->forLogic->id);
    }

    /** @test */
    public function it_has_an_admin_logic(){
        $activity = factory(Activity::class)->create();
        $this->assertInstanceOf(Logic::class, $activity->adminLogic);
        $this->assertEquals($activity->admin_logic, $activity->adminLogic->id);
    }

    /** @test */
    public function it_creates_a_slug_when_being_created()
    {
        $activity = factory(Activity::class)->make(['name' => 'A Sluggable Name']);
        $activity->slug = null;
        $activity->save();
        $this->assertEquals($activity->slug, 'a-sluggable-name');
    }

    /** @test */
    public function it_does_not_create_a_slug_if_the_slug_is_given()
    {
        $activity = factory(Activity::class)->make(['name' => 'A Sluggable Name']);
        $activity->slug = 'a-sluggable-name-two';
        $activity->save();
        $this->assertEquals($activity->slug, 'a-sluggable-name-two');
    }
    
    /** @test */
    public function isCompletable_returns_true_if_the_activity_is_a_multicompletable_activity(){
        $activity = factory(Activity::class)->create(['type' => 'multi-completable']);
        $this->assertTrue($activity->isCompletable());
    }

    /** @test */
    public function isCompletable_returns_true_if_the_activity_is_a_completable_activity(){
        $activity = factory(Activity::class)->create(['type' => 'completable']);
        $this->assertTrue($activity->isCompletable());
    }

    /** @test */
    public function isCompletable_returns_false_if_the_activity_is_an_open_activity(){
        $activity = factory(Activity::class)->create(['type' => 'open']);
        $this->assertFalse($activity->isCompletable());
    }
    
    /** @test */
    public function it_has_many_activity_instances(){
        $activity = factory(Activity::class)->create();
        $activityInstance1 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);
        $activityInstance2 = factory(ActivityInstance::class)->create(['activity_id' => $activity->id]);

        $activityInstances = $activity->activityInstances;

        $this->assertModelEquals($activityInstance1, $activityInstances->offsetGet(0));
        $this->assertModelEquals($activityInstance2, $activityInstances->offsetGet(1));
    }
}
