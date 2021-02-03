<?php

namespace BristolSU\Support\ModuleInstance\Scheduler;

use BristolSU\Support\ModuleInstance\Contracts\Scheduler\CommandStore as CommandStoreContract;

/**
 * Holds information about scheduled commands.
 */
class CommandStore implements CommandStoreContract
{
    /**
     * Holds the registered commands.
     *
     * Held in the form
     * [
     *      'module_alias' => [
     *          CommandOne::class => '* * * * *', ...
     *      ], ...
     * ]
     *
     * @var array
     */
    private $commands = [];

    /**
     * Register a new scheduled command.
     *
     * @param string $alias Module alias registering the command
     * @param string $command Command to run
     * @param string $cron Cron string to represent the frequency of the command running
     *
     */
    public function schedule($alias, $command, $cron)
    {
        if (!array_key_exists($alias, $this->commands)) {
            $this->commands[$alias] = [];
        }
        $this->commands[$alias][$command] = $cron;
    }

    /**
     * Retrieve all scheduled commands.
     *
     * @return array ['module_alias' => [CommandName::class => 'cron', ...], ... ]
     */
    public function all()
    {
        return $this->commands;
    }

    /**
     * Get all scheduled commands for a given module.
     *
     * @param string $alias Alias of the module
     * @return array [ CommandName::class => '* * * * *', ... ]
     */
    public function forAlias(string $alias)
    {
        return (isset($this->commands, $alias) ?
            $this->commands[$alias] : []);
    }
}
