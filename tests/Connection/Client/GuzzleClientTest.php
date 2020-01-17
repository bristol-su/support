<?php

namespace BristolSU\Support\Tests\Connection\Client;

use BristolSU\Support\Connection\Client\GuzzleClient;
use BristolSU\Support\Tests\TestCase;
use GuzzleHttp\ClientInterface as BaseGuzzleInterface;
use GuzzleHttp\Psr7\Response;

class GuzzleClientTest extends TestCase
{

    /** @test */
    public function it_calls_the_request_function_of_the_client(){
        $response = new Response(200, [], json_encode(['some' => 'body']));
        
        $client = $this->prophesize(BaseGuzzleInterface::class);
        $client->request('post', '/a', ['form_params' => ['test' => 1]])->shouldBeCalled()->willReturn($response);
        
        $guzzleClient = new GuzzleClient($client->reveal());
        $this->assertEquals($response, $guzzleClient->request('post', '/a', ['form_params' => ['test' => 1]]));
    }

}