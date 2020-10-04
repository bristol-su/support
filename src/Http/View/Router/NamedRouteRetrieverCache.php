<?php

namespace BristolSU\Support\Http\View\Router;

use Illuminate\Contracts\Cache\Repository;

class NamedRouteRetrieverCache implements NamedRouteRetrieverInterface
{

    /**
     * @var NamedRouteRetrieverInterface
     */
    private NamedRouteRetrieverInterface $retriever;
    /**
     * @var Repository
     */
    private Repository $cache;

    public function __construct(NamedRouteRetrieverInterface $retriever, Repository $cache)
    {
        $this->retriever = $retriever;
        $this->cache = $cache;
    }

    public function all(): array
    {
        return $this->cache->remember(NamedRouteRetrieverCache::class . '.all', 600, function() {
            return $this->retriever->all();
        });
    }

    public function currentRouteName(): ?string
    {
        return $this->retriever->currentRouteName();
    }

}
