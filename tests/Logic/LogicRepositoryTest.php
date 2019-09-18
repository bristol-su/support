<?php

namespace BristolSU\Support\Tests\Logic;

use App\Packages\ControlDB\Models\GroupTag;
use BristolSU\Support\Logic\Filters\GroupTagged;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\LogicRepository;
use BristolSU\Support\Testing\TestCase;

class LogicRepositoryTest extends TestCase
{

    /** @test */
    public function it_creates_a_logic_model()
    {
        $logicRepository = new LogicRepository;

        $logic = $logicRepository->create([
            'name' => 'LogicName',
            'description' => 'LogicDescription',
            'for' => 'group',
        ]);

        $this->assertDatabaseHas('logics', [
            'name' => 'LogicName',
            'description' => 'LogicDescription',
            'for' => 'group',
        ]);
    }

    /** @test */
    public function it_retrieves_all_logic(){
        $logics = factory(Logic::class, 10)->create();
        $logicRepository = new LogicRepository;
        $allLogics = $logicRepository->all();

        foreach($logics as $logic) {
            $this->assertModelEquals($logic, $allLogics->shift());
        }
    }

}
