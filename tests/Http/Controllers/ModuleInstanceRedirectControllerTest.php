<?php

namespace BristolSU\Support\Tests\Http\Controllers;

use BristolSU\Support\Testing\CreatesModuleEnvironment;
use BristolSU\Support\Tests\TestCase;

class ModuleInstanceRedirectControllerTest extends TestCase
{
    use CreatesModuleEnvironment;

    public function setUp(): void
    {
        parent::setUp();
        $this->createModuleEnvironment('module-alias');
    }

    /** @test */
    public function it_redirects_an_admin_module_instance_to_the_correct_url()
    {
        $response = $this->get('/a/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug);
        $response->assertRedirect('/a/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias);
    }

    /** @test */
    public function it_redirects_a_participant_module_instance_to_the_correct_url()
    {
        $response = $this->get('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug);
        $response->assertRedirect('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias);
    }

    /** @test */
    public function it_passes_any_query_headers_to_the_redirect()
    {
        $response = $this->get('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '?another=four&test=two');
        $response->assertRedirect('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias . '?another=four&test=two');
    }

    /** @test */
    public function it_sorts_query_strings_alphabetically()
    {
        $response = $this->get('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '?test=two&another=four');
        $response->assertRedirect('/p/' . $this->moduleInstance->activity->slug . '/' . $this->moduleInstance->slug . '/' . $this->moduleInstance->alias . '?another=four&test=two');
    }
}
