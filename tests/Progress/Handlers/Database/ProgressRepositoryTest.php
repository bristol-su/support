<?php


namespace BristolSU\Support\Tests\Progress\Handlers\Database;

use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
use BristolSU\Support\Progress\Handlers\Database\ProgressRepository;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ProgressRepositoryTest extends TestCase
{
    /** @test */
    public function recent_ids_gets_the_most_recent_progress_id_for_each_activity_instance()
    {
        $oldTS1 = Carbon::now()->subDay();
        $newTS1 = Carbon::now()->subHour();
        $oldTS2 = Carbon::now()->subDay()->subSeconds(5);
        $newTS2 = Carbon::now()->subHour()->subSeconds(5);
        $progress1_1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => $oldTS1]);
        $progress1_2 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => $newTS1]);
        $progress2_1 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => $oldTS2]);
        $progress2_2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => $newTS2]);

        $repository = new ProgressRepository();
        $ids = $repository->recentIds();
        $this->assertIsArray($ids);
        $this->assertCount(2, $ids);
        $this->assertContains($progress1_2->id, $ids);
        $this->assertContains($progress2_2->id, $ids);
    }

    /** @test */
    public function recent_ids_returns_the_progress_with_the_highest_id_for_an_activity_instance_if_two_timestamps_are_equal()
    {
        $ts1 = Carbon::now()->subDay();
        $oldTS2 = Carbon::now()->subDay()->subSeconds(5);
        $newTS2 = Carbon::now()->subHour()->subSeconds(5);
        $progress1_1 = factory(Progress::class)->create(['id' => 1, 'activity_instance_id' => 1, 'timestamp' => $ts1, 'created_at' => $ts1]);
        $progress1_2 = factory(Progress::class)->create(['id' => 2, 'activity_instance_id' => 1, 'timestamp' => $ts1, 'created_at' => $ts1]);
        $progress2_1 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => $oldTS2]);
        $progress2_2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => $newTS2]);

        $repository = new ProgressRepository();
        $ids = $repository->recentIds();
        $this->assertIsArray($ids);
        $this->assertCount(2, $ids);
        $this->assertContains(2, $ids);
        $this->assertContains($progress2_2->id, $ids);
    }

    /** @test */
    public function recent_ids_returns_one_progress_if_there_is_only_one_activity_instance_id()
    {
        $ts1 = Carbon::now()->subDay()->subSeconds(5);
        $ts2 = Carbon::now()->subDay();
        $ts3 = Carbon::now()->subHour()->subSeconds(5);
        $ts4 = Carbon::now()->subHour();
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => $ts1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => $ts2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => $ts3]);
        $progress4 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => $ts4]);

        $repository = new ProgressRepository();
        $ids = $repository->recentIds();
        $this->assertIsArray($ids);
        $this->assertCount(1, $ids);
        $this->assertEquals($progress4->id, $ids[0]);
    }

    /** @test */
    public function search_recent_only_returns_progresses_with_the_given_activity_instance_ids()
    {
        $progress1_1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subDay()]);
        $progress1_2 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subHour()]);
        $progress2_1 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => Carbon::now()->subDay()]);
        $progress2_2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => Carbon::now()->subHour()]);
        $progress3_1 = factory(Progress::class)->create(['activity_instance_id' => 3, 'timestamp' => Carbon::now()->subDay()]);
        $progress3_2 = factory(Progress::class)->create(['activity_instance_id' => 3, 'timestamp' => Carbon::now()->subHour()]);
        $progress4_1 = factory(Progress::class)->create(['activity_instance_id' => 4, 'timestamp' => Carbon::now()->subDay()]);
        $progress4_2 = factory(Progress::class)->create(['activity_instance_id' => 4, 'timestamp' => Carbon::now()->subHour()]);

        $repository = new ProgressRepository();
        $result = $repository->searchRecent(
            [1, 2]
        );

        $this->assertEquals(2, $result->count());
        $this->assertTrue($result->contains(function ($progress) use ($progress1_2) {
            return $progress->id === $progress1_2->id;
        }));
        $this->assertTrue($result->contains(function ($progress) use ($progress2_2) {
            return $progress->id === $progress2_2->id;
        }));
    }

    /** @test */
    public function search_recent_orders_by_the_given_order_by()
    {
        $progress1 = factory(Progress::class)->create(['id' => 1, 'activity_instance_id' => 1, 'timestamp' => Carbon::now()->subHour(), 'percentage' => 50]);
        $progress2 = factory(Progress::class)->create(['id' => 2, 'activity_instance_id' => 2, 'timestamp' => Carbon::now()->subHour()->subMinute(), 'percentage' => 70]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1, 2], 'percentage', true);
        $this->assertEquals(2, $result1->count());
        $this->assertModelEquals($progress2, $result1->offsetGet(0));
        $this->assertModelEquals($progress1, $result1->offsetGet(1));

        $result2 = $repository->searchRecent([1, 2], 'percentage', false);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));

        $result3 = $repository->searchRecent([1, 2], 'timestamp', true);
        $this->assertEquals(2, $result3->count());
        $this->assertModelEquals($progress1, $result3->offsetGet(0));
        $this->assertModelEquals($progress2, $result3->offsetGet(1));

        $result4 = $repository->searchRecent([1, 2], 'timestamp', false);
        $this->assertEquals(2, $result4->count());
        $this->assertModelEquals($progress2, $result4->offsetGet(0));
        $this->assertModelEquals($progress1, $result4->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_incomplete()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'complete' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'complete' => 1]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'complete' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'complete' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'complete' => 1]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'complete' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_incomplete()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'complete' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'complete' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'complete' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'complete' => 0]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'complete' => 0]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'complete' => 0]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'complete' => 1]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'complete' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_complete()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'complete' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'complete' => 0]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'complete' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'complete' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'complete' => 0]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'complete' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_complete()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'complete' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'complete' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'complete' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'complete' => 1]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'complete' => 1]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'complete' => 1]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'complete' => 0]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'complete' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_hidden()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'visible' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'visible' => 1]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'visible' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'visible' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'visible' => 1]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'visible' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_hidden()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'visible' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'visible' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'visible' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'visible' => 0]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'visible' => 0]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'visible' => 0]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'visible' => 1]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'visible' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_visible()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'visible' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'visible' => 0]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'visible' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'visible' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'visible' => 0]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'visible' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_visible()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'visible' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'visible' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'visible' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'visible' => 1]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'visible' => 1]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'visible' => 1]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'visible' => 0]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'visible' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [], [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_inactive()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'active' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'active' => 1]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'active' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'active' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'active' => 1]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'active' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_inactive()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'active' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'active' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'active' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'active' => 0]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'active' => 0]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'active' => 0]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'active' => 1]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'active' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [], [], [], [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_active()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'active' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'active' => 0]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'active' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'active' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'active' => 0]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'active' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_active()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'active' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'active' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'active' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'active' => 1]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'active' => 1]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'active' => 1]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'active' => 0]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'active' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [], [], [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_optional()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'mandatory' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'mandatory' => 1]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'mandatory' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'mandatory' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'mandatory' => 1]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'mandatory' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [], [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [], [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_optional()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'mandatory' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'mandatory' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'mandatory' => 0]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'mandatory' => 0]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'mandatory' => 0]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'mandatory' => 0]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'mandatory' => 1]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'mandatory' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [], [], [], [], [], [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [], [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_a_given_module_is_mandatory()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'mandatory' => 0]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'mandatory' => 0]);
        $module1_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 1, 'mandatory' => 1]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'mandatory' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'mandatory' => 0]);
        $module2_3 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress3->id, 'module_instance_id' => 2, 'mandatory' => 1]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [], [1]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress3, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [], [2]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_filter_to_show_progresses_where_given_modules_are_mandatory()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'mandatory' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'mandatory' => 0]);
        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'mandatory' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'mandatory' => 1]);
        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'mandatory' => 1]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'mandatory' => 1]);
        $module4_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 4, 'mandatory' => 0]);
        $module4_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 4, 'mandatory' => 0]);

        $repository = new ProgressRepository();

        $result1 = $repository->searchRecent([1,2], 'percentage', false, [], [], [], [], [], [], [1,3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        $result2 = $repository->searchRecent([1,2,3], 'percentage', false, [], [], [], [], [], [], [2,3]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress1, $result2->offsetGet(0));
        $this->assertModelEquals($progress2, $result2->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_combine_similar_module_status_filtering()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 1, 'complete' => 1]);
        $module1_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 1, 'complete' => 0]);

        $module2_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 2, 'complete' => 1]);
        $module2_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 2, 'complete' => 1]);

        $module3_1 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress1->id, 'module_instance_id' => 3, 'complete' => 0]);
        $module3_2 = factory(ModuleInstanceProgress::class)->create(['progress_id' => $progress2->id, 'module_instance_id' => 3, 'complete' => 0]);

        $repository = new ProgressRepository();

        // Has completed 1 and 2, has not completed 3
        $result1 = $repository->searchRecent([1,2], 'percentage', false, [3], [1,2]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        // Has not completed 1, has completed 2 and has not completed 3
        $result2 = $repository->searchRecent([1,2], 'percentage', false, [1,3], [2]);
        $this->assertEquals(1, $result2->count());
        $this->assertModelEquals($progress2, $result2->offsetGet(0));

        // Has completed 2, has not completed 3
        $result3 = $repository->searchRecent([1,2], 'percentage', false, [3], [2]);
        $this->assertEquals(2, $result3->count());
        $this->assertModelEquals($progress1, $result3->offsetGet(0));
        $this->assertModelEquals($progress2, $result3->offsetGet(1));
    }

    /** @test */
    public function search_recent_can_combine_different_module_status_filtering()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 1]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 2]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 3]);
        $progress4 = factory(Progress::class)->create(['activity_instance_id' => 4, 'percentage' => 4]);
        $module1_1 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress1->id, 'module_instance_id' => 1, 'complete' => 1, 'visible' => 1, 'active' => 0, 'mandatory' => 1]
        );
        $module1_2 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress2->id, 'module_instance_id' => 1, 'complete' => 0, 'visible' => 0, 'active' => 1, 'mandatory' => 0]
        );
        $module1_3 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress3->id, 'module_instance_id' => 1, 'complete' => 0, 'visible' => 0, 'active' => 0, 'mandatory' => 0]
        );
        $module1_4 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress4->id, 'module_instance_id' => 1, 'complete' => 0, 'visible' => 1, 'active' => 1, 'mandatory' => 1]
        );

        $module2_1 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress1->id, 'module_instance_id' => 2, 'complete' => 1, 'visible' => 0, 'active' => 0, 'mandatory' => 1]
        );
        $module2_2 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress2->id, 'module_instance_id' => 2, 'complete' => 1, 'visible' => 1, 'active' => 1, 'mandatory' => 1]
        );
        $module2_3 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress3->id, 'module_instance_id' => 2, 'complete' => 1, 'visible' => 1, 'active' => 0, 'mandatory' => 1]
        );
        $module2_4 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress4->id, 'module_instance_id' => 2, 'complete' => 1, 'visible' => 1, 'active' => 1, 'mandatory' => 0]
        );

        $module3_1 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress1->id, 'module_instance_id' => 3, 'complete' => 0, 'visible' => 0, 'active' => 0, 'mandatory' => 0]
        );
        $module3_2 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress2->id, 'module_instance_id' => 3, 'complete' => 1, 'visible' => 0, 'active' => 0, 'mandatory' => 1]
        );
        $module3_3 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress3->id, 'module_instance_id' => 3, 'complete' => 1, 'visible' => 1, 'active' => 1, 'mandatory' => 1]
        );
        $module3_4 = factory(ModuleInstanceProgress::class)->create(
            ['progress_id' => $progress4->id, 'module_instance_id' => 3, 'complete' => 0, 'visible' => 0, 'active' => 1, 'mandatory' => 1]
        );

        $repository = new ProgressRepository();

        // Has completed 1 and 2, and 1 and 2 are mandatory
        $result1 = $repository->searchRecent([1,2,3,4], 'percentage', false, [3], [1,2], [3,2], [], [], [3], [1,2], [3]);
        $this->assertEquals(1, $result1->count());
        $this->assertModelEquals($progress1, $result1->offsetGet(0));

        // Has not completed 1 (optional), has completed 2 and 3 (mandatory)
        $result2 = $repository->searchRecent([1,2,3,4], 'percentage', false, [1], [2,3], [], [], [], [], [2,3], [1]);
        $this->assertEquals(2, $result2->count());
        $this->assertModelEquals($progress2, $result2->offsetGet(0));
        $this->assertModelEquals($progress3, $result2->offsetGet(1));

        // Has completed 2. 3 is not visible. 1 is visible, active, not completed and is mandatory
        $result3 = $repository->searchRecent([1,2,3,4], 'percentage', false, [1], [2], [3], [1], [1], [], [1], []);
        $this->assertEquals(1, $result3->count());
        $this->assertModelEquals($progress4, $result3->offsetGet(0));
    }

    /** @test */
    public function search_recent_can_filter_by_percentage()
    {
        $progress1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'percentage' => 22.12]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'percentage' => 32.43]);
        $progress3 = factory(Progress::class)->create(['activity_instance_id' => 3, 'percentage' => 50.00]);
        $progress4 = factory(Progress::class)->create(['activity_instance_id' => 4, 'percentage' => 88.92]);
        $progress5 = factory(Progress::class)->create(['activity_instance_id' => 5, 'percentage' => 99.87]);

        $repository = new ProgressRepository();
        $result = $repository->searchRecent([1,2,3,4,5], 'percentage', false, [], [], [], [], [], [], [], [], 32.43, 88.92);
        $this->assertEquals(3, $result->count());
        $this->assertModelEquals($progress2, $result->offsetGet(0));
        $this->assertModelEquals($progress3, $result->offsetGet(1));
        $this->assertModelEquals($progress4, $result->offsetGet(2));
    }

    /** @test */
    public function all_for_activity_instance_returns_all_progresses_for_an_activity_instance_ordered_by_timestamp()
    {
        $progress1_6 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()]);
        $progress1_2 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subDays(5)]);
        $progress1_5 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subDays(2)]);
        $progress1_4 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subDays(3)]);
        $progress1_3 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subDays(4)]);
        $progress1_1 = factory(Progress::class)->create(['activity_instance_id' => 1, 'timestamp' => Carbon::now()->subDays(7)]);
        $progress2 = factory(Progress::class)->create(['activity_instance_id' => 2, 'timestamp' => Carbon::now()->subDays(2)]);
        $progress3_1 = factory(Progress::class)->create(['activity_instance_id' => 3, 'timestamp' => Carbon::now()->subDays(2)->subHours(12)]);
        $progress3_2 = factory(Progress::class)->create(['activity_instance_id' => 3, 'timestamp' => Carbon::now()->subDays(4)]);
        $progress4 = factory(Progress::class)->create(['activity_instance_id' => 4, 'timestamp' => Carbon::now()->subDays(3)->subHours(16)]);

        $repository = new ProgressRepository();
        $result = $repository->allForActivityInstance(1);
        $this->assertEquals(6, $result->count());
        $this->assertModelEquals($progress1_1, $result->offsetGet(0));
        $this->assertModelEquals($progress1_2, $result->offsetGet(1));
        $this->assertModelEquals($progress1_3, $result->offsetGet(2));
        $this->assertModelEquals($progress1_4, $result->offsetGet(3));
        $this->assertModelEquals($progress1_5, $result->offsetGet(4));
        $this->assertModelEquals($progress1_6, $result->offsetGet(5));
    }
}
