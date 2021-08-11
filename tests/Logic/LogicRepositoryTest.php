<?php

namespace BristolSU\Support\Tests\Logic;

use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicRepository;
use BristolSU\Support\Tests\TestCase;

class LogicRepositoryTest extends TestCase
{
    /** @test */
    public function it_creates_a_logic_model()
    {
        $logicRepository = new LogicRepository();

        $logic = $logicRepository->create([
            'name' => 'LogicName',
            'description' => 'LogicDescription',
        ]);

        $this->assertDatabaseHas('logics', [
            'name' => 'LogicName',
            'description' => 'LogicDescription',
        ]);
    }

    /** @test */
    public function it_retrieves_all_logic()
    {
        $logics = Logic::factory()->count(10)->create();
        $logicRepository = new LogicRepository();
        $allLogics = $logicRepository->all();

        foreach ($logics as $logic) {
            $this->assertModelEquals($logic, $allLogics->shift());
        }
    }

    /** @test */
    public function get_by_id_returns_a_filter_instance_by_id()
    {
        $logic = Logic::factory()->create();
        Logic::factory()->count(5)->create();

        $repository = new LogicRepository();
        $resolvedLogic = $repository->getById($logic->id);

        $this->assertInstanceOf(Logic::class, $resolvedLogic);
        $this->assertModelEquals($logic, $resolvedLogic);
    }

    /** @test */
    public function delete_deletes_the_filter_instance()
    {
        $logic = Logic::factory()->create();
        Logic::factory()->count(5)->create();

        $this->assertDatabaseHas('logics', ['id' => $logic->id]);

        $repository = new LogicRepository();
        $repository->delete($logic->id);

        $this->assertDatabaseMissing('logics', ['id' => $logic->id]);
    }

    /** @test */
    public function update_updates_a_filter_instance()
    {
        $logic = Logic::factory()->create([
            'name' => 'OldName', 'description' => 'OldDescription', 'user_id' => 1
        ]);
        Logic::factory()->count(5)->create();

        $this->assertDatabaseHas('logics', [
            'id' => $logic->id, 'name' => 'OldName', 'description' => 'OldDescription', 'user_id' => 1
        ]);

        $repository = new LogicRepository();
        $resolvedLogic = $repository->update($logic->id, [
            'name' => 'NewName', 'description' => 'NewDescription', 'user_id' => 2
        ]);

        $this->assertDatabaseHas('logics', [
            'id' => $logic->id, 'name' => 'NewName', 'description' => 'NewDescription', 'user_id' => 2
        ]);

        $this->assertEquals($logic->id, $resolvedLogic->id);
        $this->assertEquals('NewName', $resolvedLogic->name);
        $this->assertEquals('NewDescription', $resolvedLogic->description);
        $this->assertEquals(2, $resolvedLogic->user_id);
    }
}
