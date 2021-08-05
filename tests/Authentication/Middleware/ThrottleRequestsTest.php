<?php

namespace BristolSU\Support\Tests\Authentication\Middleware;

use BristolSU\Support\Testing\Authentication\SessionAuthentication;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ThrottleRequestsTest extends TestCase
{
    /** @test */
    public function it_throttles_based_on_ip_address_if_user_not_logged_in()
    {
        Route::middleware(['portal-throttle:3,2'])->name('test')->get('test', fn () => response('Test', 200));


        for ($i = 0; $i < 3; $i++) {
            $response = $this->get('/test', ['REMOTE_ADDR' => '192.0.0.44']);
            $response->assertStatus(200);
        }

        $response = $this->get('/test', ['REMOTE_ADDR' => '192.0.0.44']);
        $response->assertStatus(429);

        $response = $this->get('/test', ['REMOTE_ADDR' => '192.0.0.445']);
        $response->assertStatus(200);
    }

    /** @test */
    public function it_throttles_based_on_user_id()
    {
        $user = $this->newUser();
        $this->beUser($user);

        Route::middleware(['portal-throttle:3,2'])->name('test')->get('test', fn () => response('Test', 200));

        for ($i = 0; $i < 3; $i++) {
            $response = $this->get('/test');
            $response->assertStatus(200);
        }

        $response = $this->get('/test');
        $response->assertStatus(429);

        app(SessionAuthentication::class)->clearUser();

        $response = $this->get('/test');
        $response->assertStatus(200);
    }
}
