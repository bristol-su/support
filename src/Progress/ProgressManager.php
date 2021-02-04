<?php

namespace BristolSU\Support\Progress;

use BristolSU\Support\Progress\Handlers\Database\DatabaseHandler;
use BristolSU\Support\Progress\Handlers\Handler;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ProgressManager
{
    /**
     * The container instance.
     *
     * @var Container
     */
    protected $container;

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * Create a new Export manager instance.
     *
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get a export driver instance.
     *
     * @param  string|null  $driver
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function driver($driver = null)
    {
        return $this->resolve($driver ?? $this->getDefaultDriver());
    }

    /**
     * Resolve the given export instance by name.
     *
     * @param  string  $name
     * @throws \InvalidArgumentException
     * @return Handler
     *
     */
    protected function resolve($name)
    {
        $config = $this->configurationFor($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Progress exporter [{$name}] is not defined.");
        }
        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . Str::studly($config['driver']) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$config['driver']}] is not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param  array  $config
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->container, $config);
    }

    /**
     * Get the export connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function configurationFor($name)
    {
        $config = $this->container['config']["support.progress.export.{$name}"];
        if (is_null($config)) {
            return null;
        }

        return $config;
    }

    /**
     * Get the default export driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->container['config']['support.progress.default'];
    }

    public function extend($driver, Closure $callback)
    {
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }

    /**
     * @param $config
     *
     * @return DatabaseHandler
     */
    public function createDatabaseDriver($config)
    {
        return new DatabaseHandler();
    }
}
