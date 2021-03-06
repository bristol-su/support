<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;

use BristolSU\Support\ModuleInstance\Evaluator\Evaluation;
use BristolSU\Support\Tests\TestCase;

class EvaluationTest extends TestCase
{
    /** @test */
    public function the_active_status_can_be_set()
    {
        $evaluation = new Evaluation();
        $evaluation->setActive(true);
        $this->assertTrue($evaluation->active());

        $evaluation->setActive(false);
        $this->assertFalse($evaluation->active());
    }

    /** @test */
    public function the_visible_status_can_be_set()
    {
        $evaluation = new Evaluation();
        $evaluation->setVisible(true);
        $this->assertTrue($evaluation->visible());

        $evaluation->setVisible(false);
        $this->assertFalse($evaluation->visible());
    }

    /** @test */
    public function the_mandatory_status_can_be_set()
    {
        $evaluation = new Evaluation();
        $evaluation->setMandatory(true);
        $this->assertTrue($evaluation->mandatory());

        $evaluation->setMandatory(false);
        $this->assertFalse($evaluation->mandatory());
    }

    /** @test */
    public function the_complete_status_can_be_set()
    {
        $evaluation = new Evaluation();
        $evaluation->setComplete(true);
        $this->assertTrue($evaluation->complete());

        $evaluation->setComplete(false);
        $this->assertFalse($evaluation->complete());
    }

    /** @test */
    public function the_percentage_can_be_set()
    {
        $evaluation = new Evaluation();
        $evaluation->setPercentage(5.99);
        $this->assertEquals(5.99, $evaluation->percentage());

        $evaluation->setPercentage(45.9);
        $this->assertEquals(45.9, $evaluation->percentage());
    }

    /** @test */
    public function all_properties_are_false_or_0_by_default()
    {
        $evaluation = new Evaluation();
        $this->assertFalse($evaluation->active());
        $this->assertFalse($evaluation->visible());
        $this->assertFalse($evaluation->mandatory());
        $this->assertFalse($evaluation->complete());
        $this->assertEquals(0, $evaluation->percentage());
    }
    
    /** @test */
    public function all_properties_are_returned_as_an_array()
    {
        $evaluation = new Evaluation();
        $evaluation->setActive(true);
        $evaluation->setVisible(true);
        $evaluation->setMandatory(true);
        $evaluation->setComplete(true);
        $evaluation->setPercentage(4.55);
        $this->assertEquals([
            'active' => true, 'visible' => true, 'mandatory' => true, 'complete' => true, 'percentage' => 4.55
        ], $evaluation->toArray());


        $evaluation->setActive(false);
        $evaluation->setVisible(false);
        $evaluation->setMandatory(false);
        $evaluation->setComplete(false);
        $evaluation->setPercentage(89);
        $this->assertEquals([
            'active' => false, 'visible' => false, 'mandatory' => false, 'complete' => false, 'percentage' => 89
        ], $evaluation->toArray());
    }

    /** @test */
    public function all_properties_are_returned_as_a_string()
    {
        $evaluation = new Evaluation();
        $evaluation->setActive(true);
        $evaluation->setVisible(true);
        $evaluation->setMandatory(true);
        $evaluation->setComplete(true);
        $evaluation->setPercentage(4.55);
        $this->assertEquals(json_encode([
            'active' => true, 'visible' => true, 'mandatory' => true, 'complete' => true, 'percentage' => 4.55
        ]), $evaluation->toJson());
        $this->assertEquals(json_encode([
            'active' => true, 'visible' => true, 'mandatory' => true, 'complete' => true, 'percentage' => 4.55
        ]), (string) $evaluation);


        $evaluation->setActive(false);
        $evaluation->setVisible(false);
        $evaluation->setMandatory(false);
        $evaluation->setComplete(false);
        $evaluation->setPercentage(89);
        $this->assertEquals(json_encode([
            'active' => false, 'visible' => false, 'mandatory' => false, 'complete' => false, 'percentage' => 89
        ]), $evaluation->toJson());
        $this->assertEquals(json_encode([
            'active' => false, 'visible' => false, 'mandatory' => false, 'complete' => false, 'percentage' => 89
        ]), (string) $evaluation);
    }
}
