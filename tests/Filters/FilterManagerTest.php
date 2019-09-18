<?php

namespace BristolSU\Support\Tests\Filters;

use BristolSU\Support\Filters\FilterManager;
use BristolSU\Support\Testing\TestCase;

class FilterManagerTest extends TestCase
{

    /** @test */
    public function register_registers_a_filter(){
    
        $manager = new FilterManager;
        $manager->register('alias1', 'Class1');
        
        $this->assertEquals('Class1', $manager->getClassFromAlias('alias1'));
        
    }

    /** @test */
    public function getAll_gets_all_filters(){

        $manager = new FilterManager;
        $manager->register('alias1', 'Class1');
        $manager->register('alias2', 'Class2');

        $this->assertEquals([
            'alias1' => 'Class1',
            'alias2' => 'Class2'
        ], $manager->getAll());

    }
    
    
}