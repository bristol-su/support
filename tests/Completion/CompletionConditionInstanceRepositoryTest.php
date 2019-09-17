<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\Completion\CompletionConditionInstanceRepository;
use BristolSU\Support\Tests\TestCase;

class CompletionConditionInstanceRepositoryTest extends TestCase
{

    /** @test */
    public function create_creates_a_completion_condition_instance(){
        $cCIAttributes = [
            'name' => 'name1',
            'description' => 'description1',
            'alias' => 'alias1',
            'settings' => [
                'foo' => 'abc',
                'bar' => 'def',
                'baz' => 'ghi',
            ]
        ];
        
        $repository = new CompletionConditionInstanceRepository();
        $repository->create($cCIAttributes);
        
        $this->assertDatabaseHas('completion_condition_instances', [
            'name' => 'name1',
            'description' => 'description1',
            'alias' => 'alias1',
            'settings' => json_encode([
                'foo' => 'abc',
                'bar' => 'def',
                'baz' => 'ghi',
            ])
        ]);
    }

    /** @test */
    public function create_returns_the_completion_condition_instance(){
        $cCIAttributes = [
            'name' => 'name1',
            'description' => 'description1',
            'alias' => 'alias1',
            'settings' => [
                'foo' => 'abc',
                'bar' => 'def',
                'baz' => 'ghi',
            ]
        ];

        $repository = new CompletionConditionInstanceRepository();
        $completionConditionInstance = $repository->create($cCIAttributes);

        $this->assertInstanceOf(CompletionConditionInstance::class, $completionConditionInstance);
        $foundCCI = CompletionConditionInstance::findOrFail($completionConditionInstance->id);
        
        $this->assertModelEquals($foundCCI, $completionConditionInstance);
        
    }
    
    /** @test */
    public function all_returns_all_completion_condition_instances(){
        $completionConditionInstances = factory(CompletionConditionInstance::class, 10)->create();

        $repository = new CompletionConditionInstanceRepository();
        $allInstances = $repository->all();
        
        for ($i=0;$i<10;$i++) {
            $this->assertModelEquals($completionConditionInstances[$i], $allInstances[$i]);
        }
    }
    
}