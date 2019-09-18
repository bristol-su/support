<?php

namespace BristolSU\Support\Tests\Module;

use BristolSU\Support\Module\ModuleManager;
use BristolSU\Support\Testing\TestCase;

class ModuleManagerTest extends TestCase
{

    /** @test */
    public function aliases_returns_all_registered_aliases(){
        $manager = new ModuleManager();
        $manager->register('alias1');
        $manager->register('alias2');
        
        $this->assertEquals(['alias1', 'alias2'], $manager->aliases());
    }
    
    /** @test */
    public function exists_returns_true_if_an_alias_has_been_registered(){
        $manager = new ModuleManager();
        $manager->register('alias1');

        $this->assertTrue($manager->exists('alias1'));
    }
    
    /** @test */
    public function exists_returns_false_if_an_alias_has_not_been_registered(){
        $manager = new ModuleManager();
        $this->assertFalse($manager->exists('alias1'));
    }
    
}