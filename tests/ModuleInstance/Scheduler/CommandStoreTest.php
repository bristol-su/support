<?php

namespace BristolSU\Support\Tests\ModuleInstance\Scheduler;

use BristolSU\Support\ModuleInstance\Scheduler\CommandStore;
use BristolSU\Support\Tests\TestCase;

class CommandStoreTest extends TestCase
{

    /** @test */
    public function schedule_saves_the_given_cron_command_in_an_array(){
        $store = new CommandStore();
        $store->schedule('alias1', 'command1\BristolSU', '* * * * */2');
        $store->schedule('alias1', 'command2\BristolSU', '* * * * *');
        $store->schedule('alias2', 'command1\BristolSU2', '* * * * */2');
        $store->schedule('alias2', 'command2\BristolSU2', '* * * * *');
        
        $reflectionClass = new \ReflectionClass(CommandStore::class);
        $commandProperty = $reflectionClass->getProperty('commands');
        $commandProperty->setAccessible(true);
        
        $commands = $commandProperty->getValue($store);
        
        $this->assertEquals([
            'alias1' => [
                'command1\BristolSU' => '* * * * */2',
                'command2\BristolSU' => '* * * * *',
            ],
            'alias2' => [
                'command1\BristolSU2' => '* * * * */2',
                'command2\BristolSU2' => '* * * * *',
            ]            
        ], $commands);
    }
    
    /** @test */
    public function all_retrieves_all_scheduled_commands(){
        $store = new CommandStore();
        $store->schedule('alias1', 'command1\BristolSU', '* * * * */2');
        $store->schedule('alias1', 'command2\BristolSU', '* * * * *');
        $store->schedule('alias2', 'command1\BristolSU2', '* * * * */2');
        $store->schedule('alias2', 'command2\BristolSU2', '* * * * *');

        $this->assertEquals([
            'alias1' => [
                'command1\BristolSU' => '* * * * */2',
                'command2\BristolSU' => '* * * * *',
            ],
            'alias2' => [
                'command1\BristolSU2' => '* * * * */2',
                'command2\BristolSU2' => '* * * * *',
            ]
        ], $store->all());
    }

    /** @test */
    public function forAlias_retrieves_all_scheduled_commands_for_the_given_alias(){
        $store = new CommandStore();
        $store->schedule('alias1', 'command1\BristolSU', '* * * * */2');
        $store->schedule('alias1', 'command2\BristolSU', '* * * * *');
        $store->schedule('alias2', 'command1\BristolSU2', '* * * * */2');

        $this->assertEquals([
            'command1\BristolSU' => '* * * * */2',
            'command2\BristolSU' => '* * * * *',
        ], $store->forAlias('alias1'));
    }
    
    /** @test */
    public function it_is_registered_as_a_singleton(){
        app(\BristolSU\Support\ModuleInstance\Contracts\Scheduler\CommandStore::class)->schedule('alias1', 'command1\BristolSU', '* * * * */2');
        

        $this->assertEquals([
            'command1\BristolSU' => '* * * * */2',
        ], app(\BristolSU\Support\ModuleInstance\Contracts\Scheduler\CommandStore::class)->forAlias('alias1'));
    }
    
}