<?php


namespace BristolSU\Support\Tests\Activity;


use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Activity\Repository as ActivityRepository;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Prophecy\Argument;

class RepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function active_returns_all_active_activities(){
        $activeActivities = factory(Activity::class, 2)->create([
            'start_date' => null, 'end_date' => null
        ]);
        $activeActivities->push(factory(Activity::class)->create([
            'start_date' => Carbon::now()->subDay(),
            'end_date' => Carbon::now()->addDay()
        ]));
        $activeActivities->push(factory(Activity::class)->create(['enabled' => true, 'start_date' => null, 'end_date' => null]));

        $inactiveActivities = factory(Activity::class, 2)->create([
            'start_date' => Carbon::now()->addDay(),
            'end_date' => Carbon::now()->addWeek()
        ]);

        $inactiveActivities->push(factory(Activity::class)->create(['enabled' => false, 'start_date' => null, 'end_date' => null]));

        $activities = (new ActivityRepository)->active();

        $activeActivities->each(function($activity) use (&$activities){
            $this->assertModelEquals($activity, $activities->shift());
        });
        $this->assertEmpty($activities);
    }

    /**
     * @test
     */
    public function get_for_participant_returns_all_activities_relevant_to_a_participant(){
        $participantActivity = factory(Activity::class)->create();
        $adminActivity = factory(Activity::class)->create();
        $neitherActivity = factory(Activity::class)->create();

        $this->logicTester()->forLogic($participantActivity->forLogic)->alwaysPass();
        $this->logicTester()->forLogic($adminActivity->forLogic)->alwaysFail();
        $this->logicTester()->forLogic($neitherActivity->forLogic)->alwaysFail();
        $this->logicTester()->bind();

        $activitiesForUser = (new ActivityRepository)->getForParticipant();
        $this->assertCount(1, $activitiesForUser);
        $this->assertModelEquals($participantActivity, $activitiesForUser->first());
    }

    /**
     * @test
     */
    public function get_for_administrator_returns_all_activities_relevant_to_an_administrator(){
        $participantActivity = factory(Activity::class)->create();
        $adminActivity = factory(Activity::class)->create();
        $neitherActivity = factory(Activity::class)->create();

        $this->logicTester()->forLogic($adminActivity->adminLogic)->alwaysPass();
        $this->logicTester()->forLogic($participantActivity->adminLogic)->alwaysFail();
        $this->logicTester()->forLogic($neitherActivity->adminLogic)->alwaysFail();
        $this->logicTester()->bind();

        $activitiesForAdmin = (new ActivityRepository)->getForAdministrator();
        $this->assertCount(1, $activitiesForAdmin);
        $this->assertModelEquals($adminActivity, $activitiesForAdmin->first());
    }

    /**
     * @test
     */
    public function get_for_participant_returns_an_empty_collection_if_no_participant_activities_are_found(){
        $adminActivity = factory(Activity::class)->create();
        $neitherActivity = factory(Activity::class)->create();

        $this->logicTester()->forLogic($adminActivity->forLogic)->alwaysFail();
        $this->logicTester()->forLogic($neitherActivity->forLogic)->alwaysFail();
        $this->logicTester()->bind();

        $activitiesForUser = (new ActivityRepository)->getForParticipant();
        $this->assertInstanceOf(Collection::class, $activitiesForUser);
        $this->assertEmpty($activitiesForUser);
    }

    /**
     * @test
     */
    public function get_for_administrator_returns_an_empty_collection_if_no_administrator_activities_are_found(){
        $participantActivity = factory(Activity::class)->create();
        $neitherActivity = factory(Activity::class)->create();

        $this->logicTester()->forLogic($participantActivity->adminLogic)->alwaysFail();
        $this->logicTester()->forLogic($neitherActivity->adminLogic)->alwaysFail();
        $this->logicTester()->bind();

        $activitiesForAdmin = (new ActivityRepository)->getForAdministrator();
        $this->assertInstanceOf(Collection::class, $activitiesForAdmin);
        $this->assertEmpty($activitiesForAdmin);
    }

    /** @test */
    public function all_retrieves_all_activities(){
        $activities = factory(Activity::class, 10)->create();
        $activities->push(factory(Activity::class)->state('always_active')->create());
        $activities->push(factory(Activity::class)->state('inactive')->create());
        $repository = new ActivityRepository();
        $allActivities = $repository->all();
        foreach($activities as $activity) {
            $this->assertModelEquals($activity, $allActivities->shift());
        }
    }

    /** @test */
    public function create_creates_an_activity(){
        $attributes = [
            'name' => 'activity name',
            'description' => 'This is some activity here',
            'activity_for' => 'user',
            'type' => 'open',
            'for_logic' => factory(Logic::class)->create()->id,
            'admin_logic' => factory(Logic::class)->create()->id,
            'start_date' => Carbon::now()->subDay()->toDateTimeString(),
            'end_date' => Carbon::now()->addDay()->toDateTimeString(),
        ];

        $repository = new ActivityRepository;
        $repository->create($attributes);
        $this->assertDatabaseHas('activities', $attributes);
    }

    /** @test */
    public function getForAdministrator_passes_a_user_to_the_logic_tester(){
        $activity = factory(Activity::class)->create();
        $user = $this->newUser();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->adminLogic->id;
        }), Argument::that(function($arg) use ($user) {
            return $user instanceof User && $user->id === $arg->id;
        }),  null, null)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForAdministrator($user);
    }

    /** @test */
    public function getForAdministrator_passes_a_group_to_the_logic_tester(){
        $activity = factory(Activity::class)->create();
        $group = $this->newGroup();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->adminLogic->id;
        }), null, $group, null)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForAdministrator(null, $group);
    }

    /** @test */
    public function getForAdministrator_passes_a_role_to_the_logic_tester(){
        $activity = factory(Activity::class)->create();
        $role = $this->newRole();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->adminLogic->id;
        }), null, null, $role)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForAdministrator(null, null, $role);
    }

    /** @test */
    public function getForAdministrator_passes_null_to_the_logic_tester_if_no_user_group_and_role_given(){
        $activity = factory(Activity::class)->create();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->adminLogic->id;
        }), null,  null, null)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForAdministrator();
    }


    /** @test */
    public function getForParticipant_passes_a_user_to_the_logic_tester(){
        $activity = factory(Activity::class)->create();
        $user = $this->newUser();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->forLogic->id;
        }), Argument::that(function($arg) use ($user) {
            return $user instanceof User && $user->id === $arg->id;
        }),  null, null)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForParticipant($user);
    }

    /** @test */
    public function getForParticipant_passes_a_group_to_the_logic_tester(){
        $activity = factory(Activity::class)->create();
        $group = $this->newGroup();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->forLogic->id;
        }), null, $group, null)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForParticipant(null, $group);
    }

    /** @test */
    public function getForParticipant_passes_a_role_to_the_logic_tester(){
        $activity = factory(Activity::class)->create();
        $role = $this->newRole();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->forLogic->id;
        }), null, null, $role)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForParticipant(null, null, $role);
    }

    /** @test */
    public function getForParticipant_passes_null_to_the_logic_tester_if_no_user_group_and_role_given(){
        $activity = factory(Activity::class)->create();
        $logicTester = $this->prophesize(LogicTester::class);
        $logicTester->evaluate(Argument::that(function($arg) use ($activity) {
            return $arg->id === $activity->forLogic->id;
        }), null,  null, null)->shouldBeCalled()->willReturn(true);

        $this->instance(LogicTester::class, $logicTester->reveal());

        (new ActivityRepository)->getForParticipant();
    }

    /** @test */
    public function getById_returns_an_activity_by_id(){
        $activity = factory(Activity::class)->create();
        $repository = new ActivityRepository();

        $this->assertModelEquals($activity, $repository->getById($activity->id));
    }

    /** @test */
    public function getById_throws_an_exception_if_no_model_found(){
        $this->expectException(ModelNotFoundException::class);
        $repository = new ActivityRepository();
        $repository->getById(100);
    }

    /** @test */
    public function delete_deletes_an_activity(){
        $activity = factory(Activity::class)->create();
        $repository = new ActivityRepository();

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
        ]);

        $repository->delete($activity->id);

        $this->assertDatabaseMissing('activities', [
            'id' => $activity->id,
        ]);
    }

    /** @test */
    public function update_updates_an_activity(){
        $attributesOld = [
            'name' => 'activity name',
            'description' => 'This is some activity here',
            'activity_for' => 'user',
            'type' => 'open',
            'for_logic' => 1,
            'admin_logic' => 2,
            'start_date' => '2019-01-01 01:01:01',
            'end_date' => '2020-01-01 01:01:01',
        ];

        $attributesNew = [
            'name' => 'activity name New',
            'description' => 'This is some activity here New',
            'activity_for' => 'group',
            'type' => 'completable',
            'for_logic' => 3,
            'admin_logic' => 4,
            'start_date' => '2019-02-01 01:01:01',
            'end_date' => '2020-02-01 01:01:01',
        ];

        $activity = factory(Activity::class)->create($attributesOld);
        $this->assertDatabaseHas('activities', $attributesOld);

        $repository = new ActivityRepository;
        $repository->update($activity->id, $attributesNew);

        $this->assertDatabaseHas('activities', $attributesNew);
    }


    /**
     * @test
     */
    public function get_for_participant_returns_only_active_activities(){
        $participantActivity = factory(Activity::class)->create();
        $disabledActivity = factory(Activity::class)->create(['enabled' => false]);
        $outOfTimeActivity = factory(Activity::class)->create(['start_date' => Carbon::now()->subYear(), 'end_date' => Carbon::now()->subMonth()]);

        $this->logicTester()->forLogic($participantActivity->forLogic)->alwaysPass();
        $this->logicTester()->forLogic($disabledActivity->forLogic)->alwaysPass();
        $this->logicTester()->forLogic($outOfTimeActivity->forLogic)->alwaysPass();
        $this->logicTester()->bind();

        $activitiesForUser = (new ActivityRepository)->getForParticipant();
        $this->assertCount(1, $activitiesForUser);
        $this->assertModelEquals($participantActivity, $activitiesForUser->first());
    }

    /**
     * @test
     */
    public function get_for_administrator_returns_activities_including_enabled_and_out_of_time(){
        $adminActivity = factory(Activity::class)->create();
        $disabledActivity = factory(Activity::class)->create(['enabled' => false]);
        $outOfTimeActivity = factory(Activity::class)->create(['start_date' => Carbon::now()->subYear(), 'end_date' => Carbon::now()->subMonth()]);


        $this->logicTester()->forLogic($adminActivity->adminLogic)->alwaysPass();
        $this->logicTester()->forLogic($disabledActivity->adminLogic)->alwaysPass();
        $this->logicTester()->forLogic($outOfTimeActivity->adminLogic)->alwaysPass();
        $this->logicTester()->bind();

        $activitiesForAdmin = (new ActivityRepository)->getForAdministrator();
        $this->assertCount(3, $activitiesForAdmin);
        $this->assertModelEquals($adminActivity, $activitiesForAdmin->shift());
        $this->assertModelEquals($disabledActivity, $activitiesForAdmin->shift());
        $this->assertModelEquals($outOfTimeActivity, $activitiesForAdmin->shift());
    }

}
