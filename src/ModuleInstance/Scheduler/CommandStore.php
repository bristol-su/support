<?php

namespace BristolSU\Support\ModuleInstance\Scheduler;

use BristolSU\Support\ModuleInstance\Contracts\Scheduler\CommandStore as CommandStoreContract;

class CommandStore implements CommandStoreContract
{

    private $commands = [];

    public function schedule($alias, $command, $cron)
    {
        if(!isset($this->commands, $alias)) {
            $this->commands[$alias] = [];
        }
        $this->commands[$alias][$command] = $cron;
    }

    public function all()
    {
        return $this->commands;
    }

    public function forAlias(string $alias)
    {
        return (isset($this->commands, $alias) ?
            $this->commands[$alias] : []);
    }
    
}