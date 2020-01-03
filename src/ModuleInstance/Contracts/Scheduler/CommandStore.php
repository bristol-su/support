<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Scheduler;

interface CommandStore
{
    public function schedule($alias, $command, $cron);

    public function forAlias(string $alias);

        
    public function all();
}