<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Scheduler;

/**
 * Holds information about scheduled commands.
 */
interface CommandStore
{
    /**
     * Register a new scheduled command.
     *
     * @param string $alias Module alias registering the command
     * @param string $command Command to run
     * @param string $cron Cron string to represent the frequency of the command running
     *
     */
    public function schedule($alias, $command, $cron);

    /**
     * Get all scheduled commands for a given module.
     *
     * @param string $alias Alias of the module
     * @return array [ CommandName::class => '* * * * *', ... ]
     */
    public function forAlias(string $alias);

    /**
     * Retrieve all scheduled commands.
     *
     * @return array ['module_alias' => [CommandName::class => 'cron', ...], ... ]
     */
    public function all();
}
