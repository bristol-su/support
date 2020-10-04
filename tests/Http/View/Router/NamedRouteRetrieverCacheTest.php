<?php


namespace BristolSU\Support\Tests\Http\View\Router;


use BristolSU\Support\Http\View\Router\NamedRouteRetrieverCache;
use BristolSU\Support\Http\View\Router\NamedRouteRetrieverInterface;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Contracts\Cache\Repository;
use Prophecy\Argument;

class NamedRouteRetrieverCacheTest extends TestCase
{

    /** @test */
    public function all_is_cached(){
        $retriever = $this->prophesize(NamedRouteRetrieverInterface::class);
        $retriever->all()->shouldBeCalledTimes(1)->willReturn(['route' => '']);
        $cache = app(Repository::class);
        $key = NamedRouteRetrieverCache::class . '.all';

        $cachedRetriever = new NamedRouteRetrieverCache($retriever->reveal(), $cache);

        $this->assertFalse($cache->has($key));
        $this->assertEquals(['route' => ''], $cachedRetriever->all());
        $this->assertTrue($cache->has($key));
        $this->assertEquals(['route' => ''], $cachedRetriever->all());
    }

    /** @test */
    public function currentRouteName_is_not_cached(){
        $retriever = $this->prophesize(NamedRouteRetrieverInterface::class);
        $retriever->currentRouteName()->shouldBeCalledTimes(2)->willReturn('test.route');

        $cache = $this->prophesize(Repository::class);
        $cache->remember(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();

        $cachedRetriever = new NamedRouteRetrieverCache($retriever->reveal(), $cache->reveal());

        $this->assertEquals('test.route', $cachedRetriever->currentRouteName());
        $this->assertEquals('test.route', $cachedRetriever->currentRouteName());
    }

}
