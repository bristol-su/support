<?php

namespace BristolSU\Support\Logic\Jobs;

use BristolSU\Support\Logic\Contracts\LogicRepository;
use BristolSU\Support\Logic\DatabaseDecorator\LogicResult;
use BristolSU\Support\Logic\Events\LogicResultCleared;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Job to cache a filter result.
 */
class ClearLogicCache implements ShouldQueue
{
    use Queueable;

    private LogicResult $logicResult;

    public function __construct(LogicResult $logicResult)
    {
        $this->logicResult = $logicResult;
    }

    public function handle()
    {
        $this->logicResult->delete();
    }
}
