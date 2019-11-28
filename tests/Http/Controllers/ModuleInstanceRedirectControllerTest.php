<?php

namespace BristolSU\Support\Tests\Http\Controllers;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Control\Models\User;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceRedirectControllerTest extends TestCase
{

    /** @test */
    public function it_redirects_an_admin_module_instance_to_the_correct_url(){
        $moduleInstance = factory(ModuleInstance::class)->create(['alias' => 'alias1']);
        $this->beUser(new User(['id' => 1]));
        $response = $this->get('/a/' . $moduleInstance->activity->slug . '/' . $moduleInstance->slug);
        $response->assertRedirect('/a/' . $moduleInstance->activity->slug . '/' . $moduleInstance->slug . '/' . $moduleInstance->alias);
    }

    /** @test */
    public function it_redirects_a_participant_module_instance_to_the_correct_url(){
        $moduleInstance = factory(ModuleInstance::class)->create(['alias' => 'alias1']);
        $this->beUser(new User(['id' => 1]));
        $response = $this->get('/p/' . $moduleInstance->activity->slug . '/' . $moduleInstance->slug);
        $response->assertRedirect('/p/' . $moduleInstance->activity->slug . '/' . $moduleInstance->slug . '/' . $moduleInstance->alias);
    }
    
}