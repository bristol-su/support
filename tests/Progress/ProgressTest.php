<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Tests\TestCase;
use Carbon\Carbon;

class ProgressTest extends TestCase
{
    /** @test */
    public function activity_instance_id_can_be_retrieved_and_set()
    {
        $progress = new Progress();
        $progress->setActivityInstanceId(1);
        $this->assertEquals(1, $progress->getActivityInstanceId());

        $progress->setActivityInstanceId(5);
        $this->assertEquals(5, $progress->getActivityInstanceId());
    }

    /** @test */
    public function activity_id_can_be_retrieved_and_set()
    {
        $progress = new Progress();
        $progress->setActivityId(1);
        $this->assertEquals(1, $progress->getActivityId());

        $progress->setActivityId(5);
        $this->assertEquals(5, $progress->getActivityId());
    }

    /** @test */
    public function timestamp_can_be_retrieved_and_set()
    {
        $progress = new Progress();
        $progress->setTimestamp(Carbon::create(2020, 06, 12, 16, 20, 49));
        $this->assertTrue(
            Carbon::create(2020, 06, 12, 16, 20, 49)->equalTo($progress->getTimestamp())
        );


        $progress->setTimestamp(Carbon::create(1820, 06, 12, 16, 20, 49));
        $this->assertTrue(
            Carbon::create(1820, 06, 12, 16, 20, 49)->equalTo($progress->getTimestamp())
        );
    }

    /** @test */
    public function percentage_can_be_retrieved_and_set()
    {
        $progress = new Progress();
        $progress->setPercentage(1);
        $this->assertEquals(1, $progress->getPercentage());

        $progress->setPercentage(5);
        $this->assertEquals(5, $progress->getPercentage());
    }

    /** @test */
    public function complete_can_be_retrieved_and_set()
    {
        $progress = new Progress();
        $progress->setComplete(true);
        $this->assertTrue($progress->isComplete());

        $progress->setComplete(false);
        $this->assertFalse($progress->isComplete());
    }

    /** @test */
    public function modules_can_be_retrieved_and_set()
    {
        $module1 = $this->prophesize(ModuleInstanceProgress::class);
        $module1->getPercentage()->shouldBeCalled()->willReturn(5);
        $module2 = $this->prophesize(ModuleInstanceProgress::class);
        $module2->getPercentage()->shouldBeCalled()->willReturn(40);
        $module3 = $this->prophesize(ModuleInstanceProgress::class);
        $module3->getPercentage()->shouldBeCalled()->willReturn(100);
        
        $progress = new Progress();

        $progress->pushModule($module1->reveal());
        $this->assertCount(1, $progress->getModules());
        $this->assertEquals(5, $progress->getModules()[0]->getPercentage());


        $progress->pushModule($module2->reveal());
        $this->assertCount(2, $progress->getModules());
        $this->assertEquals(5, $progress->getModules()[0]->getPercentage());
        $this->assertEquals(40, $progress->getModules()[1]->getPercentage());


        $progress->pushModule($module3->reveal());
        $this->assertCount(3, $progress->getModules());
        $this->assertEquals(5, $progress->getModules()[0]->getPercentage());
        $this->assertEquals(40, $progress->getModules()[1]->getPercentage());
        $this->assertEquals(100, $progress->getModules()[2]->getPercentage());
    }
    
    /** @test */
    public function create_creates_a_model_with_the_given_values()
    {
        $progress = Progress::create(
            3,
            4,
            Carbon::create(2020, 06, 12, 16, 20, 49),
            false,
            44
        );
        
        $this->assertEquals(3, $progress->getActivityId());
        $this->assertEquals(4, $progress->getActivityInstanceId());
        $this->assertFalse($progress->isComplete());
        $this->assertEquals(44, $progress->getPercentage());
        $this->assertTrue(
            Carbon::create(2020, 06, 12, 16, 20, 49)->equalTo($progress->getTimestamp())
        );
    }
}
