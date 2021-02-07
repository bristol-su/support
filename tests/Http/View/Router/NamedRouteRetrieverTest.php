<?php


namespace BristolSU\Support\Tests\Http\View\Router;

use BristolSU\Support\Http\View\Router\NamedRouteRetriever;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Routing\Router;

class NamedRouteRetrieverTest extends TestCase
{
    /** @test */
    public function it_extracts_the_url_and_methods_from_the_router()
    {
        $router = new Router($this->prophesize(Dispatcher::class)->reveal());
        $router->delete('/test/123', 'Test@test')->name('test.name');
        $router->get('/test/456', 'Test2@test2')->name('test.name.2');
        $router->getRoutes()->refreshNameLookups();

        $retriever = new NamedRouteRetriever($router);

        $this->assertEquals([
            'test.name' => 'test/123',
            'test.name.2' => 'test/456',
        ], $retriever->all());
    }

    /** @test */
    public function it_can_handle_parameters()
    {
        $router = new Router($this->prophesize(Dispatcher::class)->reveal());
        $router->delete('/test/123', 'Test@test')->name('test.name');
        $router->get('/test/456/{testing}', 'Test2@test2')->name('test.name.2');
        $router->getRoutes()->refreshNameLookups();

        $retriever = new NamedRouteRetriever($router);

        $this->assertEquals([
            'test.name' => 'test/123',
            'test.name.2' => 'test/456/{testing}'
        ], $retriever->all());
    }

    /** @test */
    public function it_ignores_any_unnamed_routes()
    {
        $router = new Router($this->prophesize(Dispatcher::class)->reveal());
        $router->delete('/test/123', 'Test@test')->name('test.name');
        $router->get('/test/4567', 'Test21@test2');
        $router->get('/test/456', 'Test2@test2')->name('test.name.2');
        $router->getRoutes()->refreshNameLookups();

        $retriever = new NamedRouteRetriever($router);

        $this->assertEquals([
            'test.name' => 'test/123',
            'test.name.2' => 'test/456'
        ], $retriever->all());
    }

    /** @test */
    public function it_gets_the_current_route_name_from_the_router()
    {
        $router = $this->prophesize(Router::class);
        $router->currentRouteName()->willReturn('route.name');

        $retriever = new NamedRouteRetriever($router->reveal());

        $this->assertEquals('route.name', $retriever->currentRouteName());
    }
}
