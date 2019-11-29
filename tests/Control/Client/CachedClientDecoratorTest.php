<?php

namespace BristolSU\Support\Tests\Control\Client;

use BristolSU\Support\Control\Client\CachedClientDecorator;
use BristolSU\Support\Control\Contracts\Client\Client;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Cache\Repository as Cache;
use Prophecy\Argument;

class CachedClientDecoratorTest extends TestCase
{

    /** @test */
    public function it_caches_a_request_if_the_request_is_a_get_request(){
        $realClient = $this->prophesize(Client::class);
        $cache = $this->prophesize(Cache::class);
        
        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())
            ->shouldBeCalled()
            ->will(function($args) {
                return $args[2]();
            });
        
        $realClient->request('get', '/a', ['some' => 'options'])->shouldBeCalled()->willReturn(['some' => 'response']);
        $client = new CachedClientDecorator($realClient->reveal(), $cache->reveal());
        $response = $client->request('get', '/a', ['some' => 'options']);
        $this->assertEquals(['some' => 'response'], $response);
    }

    /** @test */
    public function it_returns_a_cached_result_if_the_request_is_cached(){
        $realClient = $this->prophesize(Client::class);
        $cache = $this->prophesize(Cache::class);

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())
            ->shouldBeCalled()
            ->willReturn(['some' => 'response']);

        $realClient->request('get', '/a', ['some' => 'options'])->shouldNotBeCalled();
        $client = new CachedClientDecorator($realClient->reveal(), $cache->reveal());
        $response = $client->request('get', '/a', ['some' => 'options']);
        $this->assertEquals(['some' => 'response'], $response);
    }
    
    
}