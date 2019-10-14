<?php


namespace BristolSU\Support\Tests\Control\Repositories;


use BristolSU\Support\Control\Repositories\Position;
use Illuminate\Support\Collection;
use BristolSU\Support\Tests\TestCase;

class PositionTest extends TestCase
{

    /** @test */
    public function all_returns_all_positions(){
        $positions = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3]
        ];

        $this->mockControl('get', 'positions', $positions);

        $positionModels = (new Position($this->controlClient->reveal()))->all();
        $this->assertEquals(1, $positionModels[0]->id);
        $this->assertEquals(2, $positionModels[1]->id);
        $this->assertEquals(3, $positionModels[2]->id);

    }

}
