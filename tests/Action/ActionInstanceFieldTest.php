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
    
}