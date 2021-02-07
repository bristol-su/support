<?php


namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Progress\ModuleInstanceProgress;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceProgressTest extends TestCase
{
    /** @test */
    public function module_instance_id_can_be_got_and_set()
    {
        $progress = new ModuleInstanceProgress();
        $progress->setModuleInstanceId(4);
        $this->assertEquals(4, $progress->getModuleInstanceId());

        $progress->setModuleInstanceId(5);
        $this->assertEquals(5, $progress->getModuleInstanceId());
    }

    /** @test */
    public function mandatory_can_be_got_and_set()
    {
        $progress = new ModuleInstanceProgress();
        $progress->setMandatory(true);
        $this->assertTrue($progress->isMandatory());

        $progress->setMandatory(false);
        $this->assertFalse($progress->isMandatory());
    }

    /** @test */
    public function visible_can_be_got_and_set()
    {
        $progress = new ModuleInstanceProgress();
        $progress->setVisible(true);
        $this->assertTrue($progress->isVisible());

        $progress->setVisible(false);
        $this->assertFalse($progress->isVisible());
    }

    /** @test */
    public function active_can_be_got_and_set()
    {
        $progress = new ModuleInstanceProgress();
        $progress->setActive(true);
        $this->assertTrue($progress->isActive());

        $progress->setActive(false);
        $this->assertFalse($progress->isActive());
    }

    /** @test */
    public function complete_can_be_got_and_set()
    {
        $progress = new ModuleInstanceProgress();
        $progress->setComplete(true);
        $this->assertTrue($progress->isComplete());

        $progress->setComplete(false);
        $this->assertFalse($progress->isComplete());
    }

    /** @test */
    public function progress_can_be_got_and_set()
    {
        $progress = new ModuleInstanceProgress();
        $progress->setPercentage(40);
        $this->assertEquals(40, $progress->getPercentage());

        $progress->setPercentage(58);
        $this->assertEquals(58, $progress->getPercentage());
    }

    /** @test */
    public function create_creates_a_progress_model_with_filled_in_values()
    {
        $progress = ModuleInstanceProgress::create(
            5,
            true,
            false,
            10,
            false,
            true
        );

        $this->assertTrue($progress->isMandatory());
        $this->assertTrue($progress->isVisible());
        $this->assertFalse($progress->isComplete());
        $this->assertFalse($progress->isActive());

        $this->assertEquals(5, $progress->getModuleInstanceId());
        $this->assertEquals(10, $progress->getPercentage());
    }
}
