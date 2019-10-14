<?php


namespace BristolSU\Support\Tests\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Control\Contracts\Models\Role;
use BristolSU\Support\Control\Contracts\Models\RoleTag;
use BristolSU\Support\Control\Contracts\Models\RoleTag as RoleTagModelContract;
use BristolSU\Support\Control\Contracts\Repositories\Position as PositionRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\RoleTag as RoleTagRepositoryContract;
use BristolSU\Support\Control\Models\Position;
use BristolSU\Support\Control\Models\Role as RoleModel;
use BristolSU\Support\Filters\Filters\RoleHasPosition;
use BristolSU\Support\Filters\Filters\RoleTagged;
use BristolSU\Support\Tests\TestCase;

class RoleHasPositionTest extends TestCase
{

    /** @test */
    public function it_returns_a_list_of_possible_positions(){
        $positionRepository = $this->prophesize(PositionRepository::class);

        $position1 = new Position(['id' => 1, 'name' => 'Position 1']);
        $position2 = new Position(['id' => 2, 'name' => 'Position 2']);

        $positionRepository->all()->shouldBeCalled()->willReturn(collect([$position1, $position2]));

        $roleHasPositionFilter = new RoleHasPosition($positionRepository->reveal(), $this->prophesize(RoleRepository::class)->reveal());

        $this->assertEquals([
            'position' => [
                1 => 'Position 1',
                2 => 'Position 2'
            ]
            ], $roleHasPositionFilter->options());
    }

    /** @test */
    public function it_evaluates_to_true_if_role_has_position(){
        
        $roleHasPositionFilter = new RoleHasPosition(
            $this->prophesize(PositionRepository::class)->reveal(),
            $this->prophesize(RoleRepository::class)->reveal()
        );
        
        $role = new RoleModel([
            'position_id' => 1
        ]);
        $roleHasPositionFilter->setModel($role);
        $this->assertTrue($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function it_evaluates_to_false_if_role_does_not_have_position(){
        $roleHasPositionFilter = new RoleHasPosition(
            $this->prophesize(PositionRepository::class)->reveal(),
            $this->prophesize(RoleRepository::class)->reveal()
        );
        $role = new RoleModel(['id' => 1, 'position_id' => 2]);
        $roleHasPositionFilter->setModel($role);
        $this->assertFalse($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function audience_returns_the_audience(){
        $roleRepository = $this->prophesize(RoleRepository::class);
        
        $roles = [
            new RoleModel([
                'id' => 1, 'position_id' => 1]
            ),
            new RoleModel([
                'id' => 2, 'position_id' => 1
            ]),
            new RoleModel([
                'id' => 3, 'position_id' => 2
            ])
        ];
        $roleRepository->all()->shouldBeCalled()->willReturn(collect($roles));
        
        $filter = new RoleHasPosition(
            $this->prophesize(PositionRepository::class)->reveal(),
            $roleRepository->reveal()
        );
        $audience = $filter->audience(['position' => '1']);
        $this->assertCount(2, $audience);
        $this->assertEquals(1, $audience[0]->id);
        $this->assertEquals(2, $audience[1]->id);
    }
}
