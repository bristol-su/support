<?php

namespace BristolSU\Support\Tests\Progress;

use BristolSU\Support\Progress\Handlers\AirTable\AirtableHandler;
use BristolSU\Support\Progress\Handlers\Database\DatabaseHandler;
use BristolSU\Support\Progress\Handlers\Handler;
use BristolSU\Support\Progress\Progress;
use BristolSU\Support\Progress\ProgressManager;
use BristolSU\Support\Tests\TestCase;
use Exception;
use Illuminate\Contracts\Container\Container;
use PHPUnit\Framework\Assert;

class ProgressManagerTest extends TestCase
{

    /** @test */
    public function driver_throws_an_exception_if_the_config_is_not_found(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Progress exporter [test-setup] is not defined.');

        $exportManager = new ProgressManager($this->app);
        $exportManager->driver('test-setup');
    }

    /** @test */
    public function driver_throws_an_exception_if_the_driver_cannot_be_created(){
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Driver [test-driver] is not supported.');

        $this->app['config']->set('support.progress.export.test-setup', [
            'driver' => 'test-driver'
        ]);

        $exportManager = new ProgressManager($this->app);
        $exportManager->driver('test-setup');
    }

    /** @test */
    public function a_custom_driver_function_is_invoked_if_manager_is_extended(){
        $handler = $this->prophesize(Handler::class);

        $this->app['config']->set('support.progress.export.test-setup', [
            'driver' => 'test-driver',
            'setting' => 'Val1'
        ]);

        $exportManager = new ProgressManager($this->app);

        $exportManager->extend('test-driver', function($app, $config) use ($handler) {
            Assert::assertInstanceOf(Container::class, $app);
            Assert::assertIsArray($config);
            Assert::assertEquals([
                'driver' => 'test-driver',
                'setting' => 'Val1',
            ], $config);
            return $handler->reveal();
        });
        $resolvedDriver = $exportManager->driver('test-setup');
        $this->assertEquals($handler->reveal(), $resolvedDriver);
    }

    /** @test */
    public function a_driver_function_is_called_if_the_manager_knows_how_to_create_the_driver(){
        $handler = $this->prophesize(Handler::class);

        $this->app['config']->set('support.progress.export.test-setup', [
            'driver' => 'test',
            'setting' => 'Val1'
        ]);

        $exportManager = new TestProgressManager($this->app);
        $exportManager->setTestDriverResult($handler->reveal());

        $resolvedDriver = $exportManager->driver('test-setup');
        $this->assertEquals($handler->reveal(), $resolvedDriver);
    }

    /** @test */
    public function it_returns_the_default_driver_if_no_driver_given(){
        $handler = $this->prophesize(Handler::class);

        $this->app['config']->set('support.progress.export.test-setup', [
            'driver' => 'test',
            'setting' => 'Val1'
        ]);

        $this->app['config']->set('support.progress.default', 'test-setup');

        $exportManager = new TestProgressManager($this->app);
        $exportManager->setTestDriverResult($handler->reveal());

        $resolvedDriver = $exportManager->driver();
        $this->assertEquals($handler->reveal(), $resolvedDriver);
    }

    /** @test */
    public function it_calls_the_function_on_the_driver_if_function_called_directly(){
        $item = new Progress();
        $handler = $this->prophesize(Handler::class);
        $handler->save($item)->shouldBeCalled();
        
        $this->app['config']->set('support.progress.export.test-setup', [
            'driver' => 'test',
            'setting' => 'Val1'
        ]);

        $this->app['config']->set('support.progress.default', 'test-setup');

        $exportManager = new TestProgressManager($this->app);
        $exportManager->setTestDriverResult($handler->reveal());

        $exportManager->save($item);
    }

    /** @test */
    public function it_can_create_a_database_driver(){
        $this->app['config']->set('support.progress.export.test-setup', [
            'driver' => 'database'
        ]);

        $exportManager = new ProgressManager($this->app);
        $driver = $exportManager->driver('test-setup');
        $this->assertInstanceOf(DatabaseHandler::class, $driver);
    }

}

class TestProgressManager extends ProgressManager
{
    protected $result;

    public function setTestDriverResult($result)
    {
        $this->result = $result;
    }

    public function createTestDriver()
    {
        return $this->result;
    }
}
