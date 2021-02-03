<?php

namespace BristolSU\Support\Progress;

use BristolSU\Support\Progress\Handlers\Handler;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void save(Progress $progress) Save a single progress
 * @method static void saveMany(Progress[] $progresses) Save many progresses
 * @method static Handler driver(string $driver) Use the given driver
 */
class ProgressExport extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'progress-exporter';
    }
}
