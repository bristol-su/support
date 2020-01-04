<?php


namespace BristolSU\Support\Tests\Filters\Filters\Role;


use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\ControlDB\Models\Position;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Filters\Filters\Role\RoleHasPosition;
use BristolSU\Support\Tests\TestCase;

class RoleHasPositionTest extends TestCase
{
    
    /** @test */
    public function it_returns_a_list_of_possible_positions(){
        $positionRepository = $this->prophesize(PositionRepository::class);

        $position1 = new Position(['id' => 1, 'name' => 'Position 1']);
        $position2 = new Position(['id' => 2, 'name' => 'Position 2']);

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
        
        $role = new Role([
            'position_id' => 1
        ]);
        $roleHasPositionFilter->setModel($role);
        $this->assertTrue($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function it_evaluates_to_false_if_role_does_not_have_position(){
        $roleHasPositionFilter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $role = new Role(['id' => 1, 'position_id' => 2]);
        $roleHasPositionFilter->setModel($role);
        $this->assertFalse($roleHasPositionFilter->evaluate(['position' => '1']));
    }
}
