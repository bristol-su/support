<?php

namespace BristolSU\Support\Translation\Translate\Handlers;

use BristolSU\Support\Translation\Translate\Handler;
use BristolSU\Support\Translation\Translate\TranslationManager;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Container\Container;

class CacheManager extends TranslationManager
{

    /**
     * @var Repository
     */
    private Repository $cache;

    public function __construct(Container $container, Repository $cache)
    {
        parent::__construct($container);
        $this->cache = $cache;
    }

    public function translate(string $line, string $lang): ?string
    {
        var_dump('In cache');
        return $this->cache->get(
            md5(Cache::class . $line . $lang),
            $line
        );
    }

}
