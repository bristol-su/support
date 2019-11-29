<?php


namespace BristolSU\Support\Tests\ModuleInstance\Evaluator;


use BristolSU\Support\ModuleInstance\Evaluator\Evaluation;
use BristolSU\Support\Tests\TestCase;

class EvaluationTest extends TestCase
{

    /** @test */
    public function the_active_status_can_be_set(){
        $evaluation = new Evaluation;
        $evaluation->setActive(true);
        $this->assertTrue($evaluation->active());

        $evaluation->setActive(false);
        $this->assertFalse($evaluation->active());
    }

    /** @test */
    public function the_visible_status_can_be_set(){
        $evaluation = new Evaluation;
        $evaluation->setVisible(true);
        $this->assertTrue($evaluation->visible());

        $evaluation->setVisible(false);
        $this->assertFalse($evaluation->visible());
    }

    /** @test */
    public function the_mandatory_status_can_be_set(){
        $evaluation = new Evaluation;
        $evaluation->setMandatory(true);
        $this->assertTrue($evaluation->mandatory());

        $evaluation->setMandatory(false);
        $this->assertFalse($evaluation->mandatory());
    }

    /** @test */
    public function the_complete_status_can_be_set(){
        $evaluation = new Evaluation;
        $evaluation->setComplete(true);
        $this->assertTrue($evaluation->complete());

        $evaluation->setComplete(false);
        $this->assertFalse($evaluation->complete());
    }

    /** @test */
    public function all_properties_are_false_by_default(){
        $evaluation = new Evaluation;
        $this->assertFalse($evaluation->active());
        $this->assertFalse($evaluation->visible());
        $this->assertFalse($evaluation->mandatory());
        $this->assertFalse($evaluation->complete());
    }
    
    /** @test */
    public function all_properties_are_returned_as_an_array(){
        $evaluation = new Evaluation;
        $evaluation->setActive(true);
        $evaluation->setVisible(true);
        $evaluation->setMandatory(true);
        $evaluation->setComplete(true);
        $this->assertEquals([
            'active' => true, 'visible' => true, 'mandatory' => true, 'complete' => true
        ], $evaluation->toArray());


        $evaluation->setActive(false);
        $evaluation->setVisible(false);
        $evaluation->setMandatory(false);
        $evaluation->setComplete(false);
        $this->assertEquals([
            'active' => false, 'visible' => false, 'mandatory' => false, 'complete' => false
        ], $evaluation->toArray());
    }


}
