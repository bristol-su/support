<?php


namespace BristolSU\Support\Tests\Filters\Filters\Role;

use BristolSU\ControlDB\Contracts\Repositories\Position as PositionRepository;
use BristolSU\ControlDB\Events\Role\RoleUpdated;
use BristolSU\ControlDB\Models\DataPosition;
use BristolSU\ControlDB\Models\Position;
use BristolSU\ControlDB\Models\Role;
use BristolSU\Support\Filters\Filters\Role\RoleHasPosition;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Fields\SelectField;

class RoleHasPositionTest extends TestCase
{
    /** @test */
    public function it_returns_a_list_of_possible_positions()
    {
        $positionRepository = $this->prophesize(PositionRepository::class);

        $dataPosition1 = DataPosition::factory()->create(['name' => 'Position 1']);
        $dataPosition2 = DataPosition::factory()->create(['name' => 'Position 2']);
        $position1 = Position::factory()->create(['id' => 1, 'data_provider_id' => $dataPosition1->id()]);
        $position2 = Position::factory()->create(['id' => 2, 'data_provider_id' => $dataPosition2->id()]);

        $positionRepository->all()->shouldBeCalled()->willReturn(collect([$position1, $position2]));

        $roleHasPositionFilter = new RoleHasPosition($positionRepository->reveal());

        $groups = $roleHasPositionFilter->options()->groups();
        $this->assertCount(1, $groups);
        $fields = $groups[0]->fields();
        $this->assertCount(1, $fields);
        $field = $fields[0];

        $this->assertInstanceOf(SelectField::class, $field);
        $this->assertEquals('position', $field->getId());
        $this->assertEquals([
            ['id' => '1', 'value' => 'Position 1'],
            ['id' => '2', 'value' => 'Position 2']
        ], $field->getSelectOptions());
    }

    /** @test */
    public function it_evaluates_to_true_if_role_has_position()
    {
        $roleHasPositionFilter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());

        $role = $this->newRole([
            'position_id' => 1
        ]);
        $roleHasPositionFilter->setModel($role);
        $this->assertTrue($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function it_evaluates_to_false_if_role_does_not_have_position()
    {
        $roleHasPositionFilter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $role = $this->newRole(['position_id' => 2]);
        $roleHasPositionFilter->setModel($role);
        $this->assertFalse($roleHasPositionFilter->evaluate(['position' => '1']));
    }

    /** @test */
    public function name_returns_a_string()
    {
        $filter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string()
    {
        $filter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string()
    {
        $filter = new RoleHasPosition($this->prophesize(PositionRepository::class)->reveal());
        $this->assertIsString($filter->alias());
    }

    /** @test */
    public function it_listens_to_role_updated_events_correctly(){
        $this->assertContains(RoleUpdated::class, RoleHasPosition::listensTo());

        $role = Role::factory()->create(['position_id' => 5, 'id' => 50]);
        $correctEvent = new RoleUpdated($role, ['position_id' => 5]);
        $wrongEvent = new RoleUpdated($role, ['group_id' => 10]);

        $correctResult = RoleHasPosition::clearOn()[RoleUpdated::class]($correctEvent);
        $this->assertIsInt($correctResult);
        $this->assertEquals(50, $correctResult);
        $wrongResult = RoleHasPosition::clearOn()[RoleUpdated::class]($wrongEvent);
        $this->assertFalse($wrongResult);
    }
}
