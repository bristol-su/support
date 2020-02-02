<?php


namespace BristolSU\Support\Tests\Filters\Filters\Role;


use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\ControlDB\Models\DataPosition;
use BristolSU\ControlDB\Models\Position;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Filters\Filters\Role\RoleHasPosition;
use BristolSU\Support\Tests\TestCase;

class RoleHasPositionTest extends TestCase
{
    
    /** @test */
    public function it_returns_a_list_of_possible_positions(){
        $positionRepository = $this->prophesize(PositionRepository::class);

        $dataPosition1 = factory(DataPosition::class)->create(['name' => 'Position 1']);
        $dataPosition2 = factory(DataPosition::class)->create(['name' => 'Position 2']);
        $position1 = factory(Position::class)->create(['id' => 1, 'data_provider_id' => $dataPosition1->id()]);
        $position2 = factory(Position::class)->create(['id' => 2, 'data_provider_id' => $dataPosition2->id()]);

        $positionRepository->all()->shouldBeCalled()->willReturn(collect([$position1, $position2]));

        $roleHasPositionFilter = new RoleHasPosition($positionRepository->reveal());

        $this->assertEquals([
            'position' => [
                1 => 'Position 1',
                2 => 'Position 2'
            ]
            ], $roleHasPositionFilter->options());
    }

    /** @test */
    public function it_evaluates_to_true_if_role_has_position(){
        
        $roleHasPositionFilter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        
        $role = $this->newRole([
            'position_id' => 1
        ]);
        $roleHasPositionFilter->setModel($role);
        $this->assertTrue($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function it_evaluates_to_false_if_role_does_not_have_position(){
        $roleHasPositionFilter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $role = $this->newRole(['id' => 1, 'position_id' => 2]);
        $roleHasPositionFilter->setModel($role);
        $this->assertFalse($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function name_returns_a_string(){
        $filter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string(){
        $filter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string(){
        $filter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $this->assertIsString($filter->alias());
    }
}
