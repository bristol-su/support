<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use BristolSU\Support\Tests\TestCase;

class ActionInstanceFieldTest extends TestCase
{

    /** @test */
    public function actionInstanceField_has_an_action_instance(){
        $actionInstance = factory(ActionInstance::class)->create();
        $actionInstanceField = factory(ActionInstanceField::class)->create([
            'action_instance_id' => $actionInstance->id
        ]);
        
        $this->assertModelEquals($actionInstance, $actionInstanceField->actionInstance);
    }

    /** @test */
    public function revisions_are_saved(){
        $user = $this->newUser();
        $this->beUser($user);

        $actionInstanceField = factory(ActionInstanceField::class)->create(['action_instance_id' => 1]);

        $actionInstanceField->action_instance_id = 2;
        $actionInstanceField->save();

        $this->assertEquals(1, $actionInstanceField->revisionHistory->count());
        $this->assertEquals($actionInstanceField->id, $actionInstanceField->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ActionInstanceField::class, $actionInstanceField->revisionHistory->first()->revisionable_type);
        $this->assertEquals('action_instance_id', $actionInstanceField->revisionHistory->first()->key);
        $this->assertEquals(1, $actionInstanceField->revisionHistory->first()->old_value);
        $this->assertEquals(2, $actionInstanceField->revisionHistory->first()->new_value);
    }
    
}