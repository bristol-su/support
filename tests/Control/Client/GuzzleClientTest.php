<?php

namespace BristolSU\Support\Tests\Control\Client;

use BristolSU\Support\Control\Client\GuzzleClient;
use BristolSU\Support\Control\Contracts\Client\Token;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Cache\Repository;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

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
        $cache = $this->prophesize(Repository::class);
        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal(), $cache->reveal());
        $this->assertEquals(['some' => 'body'], $guzzleClient->request('post', '/a'));
    }

    /** @test */
    public function it_merges_the_default_options_and_custom_options(){
        $client = $this->prophesize(ClientInterface::class);
        $client->request('post', '/a', Argument::that(function($options) {
            return array_key_exists('another', $options) && array_key_exists('base_uri', $options);
        }))->shouldBeCalled()->willReturn(new Response(200, [], json_encode(['some' => 'body'])));
        $cache = $this->prophesize(Repository::class);
        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal(), $cache->reveal());
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
        $cache = $this->prophesize(Repository::class);
        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal(), $cache->reveal());
        $this->assertEquals(['some' => 'body'], $guzzleClient->request('post', '/a'));
    }
    
    /** @test */
    public function it_returns_a_cached_result_for_a_get_request(){
        $client = $this->prophesize(ClientInterface::class);
        $cache = $this->prophesize(Repository::class);
        
        $cache->has('BristolSU\Support\Control\Client\GuzzleClient/a[]')->shouldBeCalled()->willReturn(true);
        $cache->get('BristolSU\Support\Control\Client\GuzzleClient/a[]')->shouldBeCalled()->willReturn(['a' => 'test']);
        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal(), $cache->reveal());
        $this->assertEquals(['a' => 'test'], $guzzleClient->request('get', '/a'));
    }

    /** @test */
    public function it_saves_a_get_request_in_the_cache(){
        $client = $this->prophesize(ClientInterface::class);
        $cache = $this->prophesize(Repository::class);
        $client->request('get', '/a', Argument::any())->shouldBeCalled()->willReturn(new Response(200, [], json_encode(['some' => 'body'])));
        
        $cache->has('BristolSU\Support\Control\Client\GuzzleClient/a[]')->shouldBeCalled()->willReturn(false);
        $cache->put('BristolSU\Support\Control\Client\GuzzleClient/a[]', ['some' => 'body'], 30)->shouldBeCalled();
        $guzzleClient = new GuzzleClient($client->reveal(), $this->token->reveal(), $cache->reveal());
        $this->assertEquals(['some' => 'body'], $guzzleClient->request('get', '/a'));
    }

}
