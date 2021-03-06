<?php


namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Progress\Handlers\Handler;
use BristolSU\Support\Progress\ProgressExport;
use BristolSU\Support\Progress\ProgressManager;
use BristolSU\Support\Tests\TestCase;
use Illuminate\Support\Facades\Facade;

class ProgressExportTest extends TestCase
{
    /** @test */
    public function it_returns_the_underlying_instance()
    {
        $handler = $this->prophesize(Handler::class);
        $progressManger = $this->prophesize(ProgressManager::class);
        $progressManger->driver('test')->shouldBeCalled()->willReturn($handler->reveal());
        
        $this->instance('progress-exporter', $progressManger->reveal());
        Facade::clearResolvedInstances();
        
        $resolvedProgressManager = ProgressExport::getFacadeRoot();
        $this->assertInstanceOf(ProgressManager::class, $resolvedProgressManager);
        $this->assertInstanceOf(Handler::class, $resolvedProgressManager->driver('test'));
    }
}
