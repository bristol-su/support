<?php

namespace BristolSU\Support\Tests\Completion;

use BristolSU\Support\Completion\CompletionConditionInstance;
use BristolSU\Support\Completion\CompletionConditionInstanceRepository;
use BristolSU\Support\Tests\TestCase;

class CompletionConditionInstanceRepositoryTest extends TestCase
{
    /** @test */
    public function create_creates_a_completion_condition_instance()
    {
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
    public function create_returns_the_completion_condition_instance()
    {
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
    public function all_returns_all_completion_condition_instances()
    {
        $completionConditionInstances = CompletionConditionInstance::factory()->count(10)->create();

        $repository = new CompletionConditionInstanceRepository();
        $allInstances = $repository->all();

        for ($i=0;$i<10;$i++) {
            $this->assertModelEquals($completionConditionInstances[$i], $allInstances[$i]);
        }
    }

    /** @test */
    public function get_by_id_returns_a_completion_condition_instance_with_the_given_id()
    {
        $ccI = CompletionConditionInstance::factory()->create();
        CompletionConditionInstance::factory()->count(5)->create();

        $repository = new CompletionConditionInstanceRepository();
        $resolvedCci = $repository->getById($ccI->id);

        $this->assertInstanceOf(CompletionConditionInstance::class, $resolvedCci);
        $this->assertModelEquals($ccI, $resolvedCci);
    }

    /** @test */
    public function update_updates_the_completion_condition_instance()
    {
        $ccI = CompletionConditionInstance::factory()->create([
            'alias'=> 'OldAlias', 'name' => 'OldName', 'description' => 'OldDescription', 'settings' => ['val' => 'OldVal']
        ]);
        CompletionConditionInstance::factory()->count(5)->create();

        $this->assertDatabaseHas('completion_condition_instances', [
            'id' => $ccI->id, 'alias'=> 'OldAlias', 'name' => 'OldName', 'description' => 'OldDescription', 'settings' => json_encode(['val' => 'OldVal'])
        ]);

        $repository = new CompletionConditionInstanceRepository();
        $resolvedCci = $repository->update($ccI->id, [
            'alias'=> 'NewAlias', 'name' => 'NewName', 'description' => 'NewDescription', 'settings' => ['val' => 'NewVal']
        ]);

        $this->assertDatabaseHas('completion_condition_instances', [
            'id' => $ccI->id, 'alias'=> 'NewAlias', 'name' => 'NewName', 'description' => 'NewDescription', 'settings' => json_encode(['val' => 'NewVal'])
        ]);

        $this->assertInstanceOf(CompletionConditionInstance::class, $resolvedCci);
        $this->assertEquals($ccI->id, $resolvedCci->id);
        $this->assertEquals('NewAlias', $resolvedCci->alias);
        $this->assertEquals('NewName', $resolvedCci->name);
        $this->assertEquals('NewDescription', $resolvedCci->description);
        $this->assertEquals(['val' => 'NewVal'], $resolvedCci->settings);
    }
}
