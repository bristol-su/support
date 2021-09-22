<?php

namespace BristolSU\Support\Tests\Filters\Filters\Group;

use BristolSU\ControlDB\Models\DataGroup;
use BristolSU\Support\Filters\Filters\Group\GroupNameIs;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Fields\TextInputField;

class GroupNameIsTest extends TestCase
{
    /** @test */
    public function options_returns_group_name_with_an_empty_string()
    {
        $filter = new GroupNameIs();

        $groups = $filter->options()->groups();
        $this->assertCount(1, $groups);
        $fields = $groups[0]->fields();
        $this->assertCount(1, $fields);
        $field = $fields[0];

        $this->assertInstanceOf(TextInputField::class, $field);
        $this->assertEquals('Group Name', $field->getId());
    }

    /** @test */
    public function evaluate_returns_true_if_the_group_name_is_equal_to_the_settings()
    {
        $dataGroup = DataGroup::factory()->create(['name' => 'group name 1']);
        $group = $this->newGroup(['data_provider_id' => $dataGroup->id()]);

        $filter = new GroupNameIs();
        $filter->setModel($group);
        $this->assertTrue($filter->evaluate(['Group Name' => 'group name 1']));
    }

    /** @test */
    public function evaluate_returns_false_if_the_group_name_is_different_to_the_settings()
    {
        $dataGroup = DataGroup::factory()->create(['name' => 'group name 1']);
        $group = $this->newGroup(['data_provider_id' => $dataGroup->id()]);

        $filter = new GroupNameIs();
        $filter->setModel($group);
        $this->assertFalse($filter->evaluate(['Group Name' => 'group name 2']));
    }

    /** @test */
    public function evaluate_ignores_case()
    {
        $dataGroup = DataGroup::factory()->create(['name' => 'group name 1']);
        $group = $this->newGroup(['data_provider_id' => $dataGroup->id()]);

        $filter = new GroupNameIs();
        $filter->setModel($group);
        $this->assertTrue($filter->evaluate(['Group Name' => 'GROUP NAME 1']));
    }

    /** @test */
    public function name_returns_a_string()
    {
        $filter = new GroupNameIs();
        $this->assertIsString($filter->name());
    }

    /** @test */
    public function description_returns_a_string()
    {
        $filter = new GroupNameIs();
        $this->assertIsString($filter->description());
    }

    /** @test */
    public function alias_returns_a_string()
    {
        $filter = new GroupNameIs();
        $this->assertIsString($filter->alias());
    }
}
