<?php


namespace BristolSU\Support\Tests\Http\View\Router;

use BristolSU\Support\Http\View\Router\InjectNamedRoutes;
use BristolSU\Support\Http\View\Router\NamedRouteRetriever;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;
use Prophecy\Argument;

class InjectNamedRoutesTest extends TestCase
{
    /** @test */
    public function it_injects_whatever_the_route_retriever_passes()
    {
        $routes = [
            'route1' => 'test/123',
            'route2' => 'test/456'
        ];

        $retriever = $this->prophesize(NamedRouteRetriever::class);
        $retriever->all()->willReturn($routes);
        $retriever->currentRouteName()->willReturn(null);

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function ($arg) use ($routes) {
            return array_key_exists('named_routes', $arg)
              && $arg['named_routes'] === $routes;
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        $injector = new InjectNamedRoutes($retriever->reveal(), $this->prophesize(Request::class)->reveal());
        $injector->compose($this->prophesize(View::class)->reveal());
    }

    /** @test */
    public function it_injects_the_current_route_name()
    {
        $retriever = $this->prophesize(NamedRouteRetriever::class);
        $retriever->all()->willReturn([]);
        $retriever->currentRouteName()->willReturn('route.example');

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function ($arg) {
            return array_key_exists('current_route', $arg)
              && $arg['current_route'] === 'route.example';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        $injector = new InjectNamedRoutes($retriever->reveal(), $this->prophesize(Request::class)->reveal());
        $injector->compose($this->prophesize(View::class)->reveal());
    }

    /** @test */
    public function it_injects_the_base_url()
    {
        $retriever = $this->prophesize(NamedRouteRetriever::class);
        $retriever->all()->willReturn([]);
        $retriever->currentRouteName()->willReturn(null);

        $request = $this->prophesize(Request::class);
        $request->getBaseUrl()->shouldBeCalled()->willReturn('https://example.com');

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(Argument::that(function ($arg) {
            return array_key_exists('base_url', $arg)
              && $arg['base_url'] === 'https://example.com';
        }))->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        $injector = new InjectNamedRoutes($retriever->reveal(), $request->reveal());
        $injector->compose($this->prophesize(View::class)->reveal());
    }
}
