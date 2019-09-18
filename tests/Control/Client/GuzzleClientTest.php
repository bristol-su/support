<?php

namespace BristolSU\Support\Tests\Control\Client;

use BristolSU\Support\Control\Client\GuzzleClient;
use BristolSU\Support\Control\Contracts\Client\Token;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Prophecy\Argument;
use BristolSU\Support\Testing\TestCase;

class GuzzleClientTest extends TestCase
{
    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $token;

    public function setUp() : void
    {
        parent::setUp();
        $token = $this->prophesize(Token::class);
        $token->token()->willReturn('SecretToken');
        $this->token = $token;
    }
    /** @test */
    public function it_calls_the_request_function_of_the_client(){
        $client = $this->prophesize(ClientInterface::class);
        $client->request('post', '/a', Argument::type('array'))->shouldBeCalled()->willReturn(
            new Response(200, [], json_encode(['some' => 'body']))
        );

        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal());
        $this->assertEquals(['some' => 'body'], $guzzleClient->request('post', '/a'));
    }

    /** @test */
    public function it_merges_the_default_options_and_custom_options(){
        $client = $this->prophesize(ClientInterface::class);
        $client->request('post', '/a', Argument::that(function($options) {
            return array_key_exists('another', $options) && array_key_exists('base_uri', $options);
        }))->shouldBeCalled()->willReturn(new Response(200, [], json_encode(['some' => 'body'])));

        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal());
        $this->assertEquals(['some' => 'body'], $guzzleClient->request('post', '/a', [
            'another' => 'value'
        ]));
    }

    /** @test */
    public function it_sets_a_token_on_the_default_options(){
        $client = $this->prophesize(ClientInterface::class);
        $client->request('post', '/a', Argument::that(function($options) {
            return $options['headers']['Authorization'] === 'Bearer SecretToken';
        }))->shouldBeCalled()->willReturn(new Response(200, [], json_encode(['some' => 'body'])));

        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal());
        $this->assertEquals(['some' => 'body'], $guzzleClient->request('post', '/a'));
    }

}
