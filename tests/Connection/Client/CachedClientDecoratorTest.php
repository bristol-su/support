<?php

namespace BristolSU\Support\Tests\Connection\Client;

use BristolSU\Support\Connection\Client\CachedClientDecorator;
use BristolSU\Support\Connection\Contracts\Client\Client;
use BristolSU\Support\Tests\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Contracts\Cache\Repository as Cache;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class CachedClientDecoratorTest extends TestCase
{

    /** @test */
    public function it_caches_a_request_if_the_request_is_a_get_request()
    {
        $realClient = $this->prophesize(Client::class);
        $cache = $this->prophesize(Cache::class);
        $clientResponse = $this->prophesize(Response::class);
        $clientResponse->getStatusCode()->shouldBeCalled()->willReturn(203);
        $clientResponse->getHeaders()->shouldBeCalled()->willReturn(['my' => 'header']);
        $clientResponse->getBody()->shouldBeCalled()->willReturn('This is the response body');
        $clientResponse->getProtocolVersion()->shouldBeCalled()->willReturn('1.2');
        $clientResponse->getReasonPhrase()->shouldBeCalled()->willReturn('Testing');

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())
            ->shouldBeCalled()
            ->will(function ($args) {
                return $args[2]();
            });

        $realClient->request('get', '/a', ['some' => 'options'])->shouldBeCalled()->willReturn($clientResponse->reveal());

        $client = new CachedClientDecorator($realClient->reveal(), $cache->reveal());
        $response = $client->request('get', '/a', ['some' => 'options']);
        $this->assertEquals(203, $response->getStatusCode());
        $this->assertEquals(['my' => ['header']], $response->getHeaders());
        $this->assertEquals('This is the response body', (string) $response->getBody());
        $this->assertEquals('1.2', $response->getProtocolVersion());
        $this->assertEquals('Testing', $response->getReasonPhrase());
    }

    /** @test */
    public function it_does_not_cache_a_request_if_the_request_is_a_post_request()
    {
        $realClient = $this->prophesize(Client::class);
        $cache = $this->prophesize(Cache::class);

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())
            ->shouldNotBeCalled();

        $realClient->request('post', '/a', ['some' => 'options'])->shouldBeCalled()->willReturn(['some' => 'response']);

        $client = new CachedClientDecorator($realClient->reveal(), $cache->reveal());
        $response = $client->request('post', '/a', ['some' => 'options']);
        $this->assertEquals(['some' => 'response'], $response);
    }

    /** @test */
    public function it_returns_a_cached_result_if_the_request_is_cached()
    {
        $realClient = $this->prophesize(Client::class);
        $cache = $this->prophesize(Cache::class);

        $cache->remember(Argument::type('string'), Argument::type('integer'), Argument::any())
            ->shouldBeCalled()
            ->willReturn([
                'status' => 203,
                'headers' => ['my' => ['header']],
                'body' => 'This is the response body',
                'version' => '1.2',
                'reason' => 'Testing'
            ]);

        $realClient->request('get', '/a', ['some' => 'options'])->shouldNotBeCalled();
        $client = new CachedClientDecorator($realClient->reveal(), $cache->reveal());
        $response = $client->request('get', '/a', ['some' => 'options']);

        $this->assertEquals(203, $response->getStatusCode());
        $this->assertEquals(['my' => ['header']], $response->getHeaders());
        $this->assertEquals('This is the response body', (string) $response->getBody());
        $this->assertEquals('1.2', $response->getProtocolVersion());
        $this->assertEquals('Testing', $response->getReasonPhrase());
    }
}
