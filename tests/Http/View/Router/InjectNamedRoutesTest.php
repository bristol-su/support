<?php


namespace BristolSU\Support\Tests\Http\View\Router;


use BristolSU\Support\Http\View\Router\InjectNamedRoutes;
use BristolSU\Support\Http\View\Router\NamedRouteRetriever;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\View\View;
use Laracasts\Utilities\JavaScript\Transformers\Transformer;

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

        $transformer = $this->prophesize(Transformer::class);
        $transformer->put(['named_routes' => $routes])->shouldBeCalled();
        $this->instance('JavaScript', $transformer->reveal());

        $injector = new InjectNamedRoutes($retriever->reveal());
        $injector->compose($this->prophesize(View::class)->reveal());
    }

}
