<?php


namespace BristolSU\Support\Tests\Control\Client;


use BristolSU\Support\Control\Client\GuzzleClient;
use BristolSU\Support\Control\Client\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Prophecy\Argument;
use BristolSU\Support\Tests\TestCase;

class TokenTest extends TestCase
{

    /** @test */
    public function it_sends_a_post_request_to_get_a_token(){
        $client = $this->prophesize(Client::class);
        $response = new Response(200, [], json_encode(['access_token' => 'SecretToken', 'expires_in' => 1]));
        $client->post(config('control.base_uri') . '/oauth/token', Argument::type('array'))->shouldBeCalled()->willReturn($response);

        $token = new Token($client->reveal());
        $token->token(true);
    }

    /** @test */
    public function it_parses_a_post_request(){
        $client = $this->prophesize(Client::class);
        $response = new Response(200, [], json_encode(['access_token' => 'SecretToken', 'expires_in' => 1]));
        $client->post(config('control.base_uri') . '/oauth/token', Argument::type('array'))->shouldBeCalled()->willReturn($response);

        $token = new Token($client->reveal());
        $accessToken = $token->token(true);
        $this->assertEquals('SecretToken', $accessToken);
    }

    /** @test */
    public function it_returns_a_cached_token_if_one_available(){
        $key = GuzzleClient::class . '@token';
        $cache = $this->prophesize(Repository::class);
        $cache->has($key)->shouldBeCalled()->willReturn(true);
        $cache->get($key)->shouldBeCalled()->willReturn('somesecretkey');
        $this->app->instance('cache', $cache->reveal());
        $client = $this->prophesize(Client::class);
        $token = new Token($client->reveal());
        $this->assertEquals('somesecretkey', $token->token());
    }

}
