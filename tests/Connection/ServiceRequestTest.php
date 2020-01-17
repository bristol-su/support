<?php

namespace BristolSU\Support\Tests\Connection;

use BristolSU\Support\Connection\ServiceRequest;
use BristolSU\Support\Tests\TestCase;

class ServiceRequestTest extends TestCase
{

    /** @test */
    public function optional_services_can_be_set_and_retrieved_for_a_module_alias(){
        $serviceRequest = new ServiceRequest();

        $serviceRequest->optional('alias1', ['typeform', 'service2', 'service3']);
        $serviceRequest->optional('alias2', ['service4', 'service5', 'service6']);

        $this->assertEquals(['typeform', 'service2', 'service3'], $serviceRequest->getOptional('alias1'));
        $this->assertEquals(['service4', 'service5', 'service6'], $serviceRequest->getOptional('alias2'));
    }

    /** @test */
    public function all_optional_services_can_be_retrieved(){
        $serviceRequest = new ServiceRequest();

        $serviceRequest->optional('alias1', ['typeform', 'service2', 'service3']);
        $serviceRequest->optional('alias2', ['service4', 'service5', 'service6']);

        $this->assertEquals([
            'alias1' => ['typeform', 'service2', 'service3'],
            'alias2' => ['service4', 'service5', 'service6']
        ], $serviceRequest->getAllOptional());
    }

    /** @test */
    public function if_no_optional_services_are_registered_by_an_alias_optional_services_returns_an_empty_array(){
        $serviceRequest = new ServiceRequest();

        $this->assertEquals([], $serviceRequest->getOptional('alias1'));
    }
    
    /** @test */
    public function required_services_can_be_set_and_retrieved_for_a_module_alias(){
        $serviceRequest = new ServiceRequest();

        $serviceRequest->required('alias1', ['typeform', 'service2', 'service3']);
        $serviceRequest->required('alias2', ['service4', 'service5', 'service6']);

        $this->assertEquals(['typeform', 'service2', 'service3'], $serviceRequest->getRequired('alias1'));
        $this->assertEquals(['service4', 'service5', 'service6'], $serviceRequest->getRequired('alias2'));
    }

    /** @test */
    public function all_required_services_can_be_retrieved(){
        $serviceRequest = new ServiceRequest();

        $serviceRequest->required('alias1', ['typeform', 'service2', 'service3']);
        $serviceRequest->required('alias2', ['service4', 'service5', 'service6']);

        $this->assertEquals([
            'alias1' => ['typeform', 'service2', 'service3'],
            'alias2' => ['service4', 'service5', 'service6']
        ], $serviceRequest->getAllRequired());
    }
    
    /** @test */
    public function if_no_required_services_are_registered_by_an_alias_required_services_returns_an_empty_array(){
        $serviceRequest = new ServiceRequest();

        $this->assertEquals([], $serviceRequest->getRequired('alias1'));
    }
    
    
    
}