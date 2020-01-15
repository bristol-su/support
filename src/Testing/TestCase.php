<?php

namespace BristolSU\Support\Testing;

use BristolSU\ControlDB\ControlDBServiceProvider;
use BristolSU\Support\SupportServiceProvider;
use Illuminate\Foundation\Application;
use Laracasts\Utilities\JavaScript\JavaScriptServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Test Case
 * 
 * Sets up a Laravel testing environment for any project using the sdk.
 */
class TestCase extends BaseTestCase
{
    use CreatesSdkEnvironment;

    /**
     * Initialise the test case.
     * 
     * Loads migrations and factories for the sdk
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
        $this->withFactories(__DIR__ . '/../../database/factories');
    }

    /**
     * Set up the Orchestra Environment
     * 
     * - Set up the memory database connection
     * - Set up the Sdk environment
     * 
     * @param Application $app Application to set up
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', 'base64:UTyp33UhGolgzCK5CJmT+hNHcA+dJyp3+oINtX+VoPI=');
        $this->createSdkEnvironment($app);
    }

    /**
     * Get the service providers the sdk registers and requires.
     * 
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            JavaScriptServiceProvider::class,
            ControlDBServiceProvider::class
        ];
    }

}
