<?php

namespace BristolSU\Support\Translation\Translate\Handlers;

use BristolSU\Support\Translation\Translate\Handler;
use BristolSU\Support\Translation\Translate\TranslationMiddleware;
use Illuminate\Contracts\Cache\Repository;

class Cache implements TranslationMiddleware
{

    /**
     * @var Repository
     */
    private Repository $cache;

    /**
     * @var TranslationMiddleware|null
     */
    private ?TranslationMiddleware $successor;

    public function __construct(Repository $cache, ?TranslationMiddleware $successor = null)
    {
        $this->cache = $cache;
        $this->successor = $successor;
    }

    public function intercept(string $line, string $lang, Handler $handler)
    {
        return $this->cache->remember(md5(Cache::class . $line . $lang), 10000, function() use ($handler) {
            return $handler->
        });
        var_dump('In cache');
        $key = md5(Cache::class . $line);
        if($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        return null;
    }
}
