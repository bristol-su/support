<?php

namespace BristolSU\Support\Tests\Action;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use BristolSU\Support\Action\History\ActionHistory;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;
use FormSchema\Transformers\Transformer;
use Illuminate\Support\Collection;
use Prophecy\Argument;

class ActionInstanceTest extends TestCase
{
    /** @test */
    public function action_instance_has_many_action_instance_fields()
    {
        $actionInstance = ActionInstance::factory()->create();
        $actionInstanceFieldsFactory = ActionInstanceField::factory()->count(10)->create([
            'action_instance_id' => $actionInstance->id
        ]);

        $actionInstanceFields = $actionInstance->actionInstanceFields;
        foreach ($actionInstanceFieldsFactory as $field) {
            $this->assertModelEquals($field, $actionInstanceFields->shift());
        }
    }

    /** @test */
    public function action_instance_has_an_event_fields_attribute()
    {
        $actionInstance = ActionInstance::factory()->create([
            'event' => ActionInstanceDummyEvent::class,
            'action' => ActionInstanceDummyAction::class
        ]);

        $this->assertEquals([
            'eventfield1' => [
                'label' => 'Event Field 1'
            ]
        ], $actionInstance->event_fields);
    }

    /** @test */
    public function action_instance_has_an_action_schema_attribute()
    {
        $actionInstance = ActionInstance::factory()->create([
            'event' => ActionInstanceDummyEvent::class,
            'action' => ActionInstanceDummyAction::class
        ]);

        $transformer = $this->prophesize(Transformer::class);
        $transformer->transformToArray(Argument::type(Form::class))->shouldBeCalled()->willReturn(['test-form']);
        $this->app->instance(Transformer::class, $transformer->reveal());

        $this->assertEquals(['test-form'], $actionInstance->action_schema);
    }

    /** @test */
    public function action_instance_has_a_module_instance()
    {
        $moduleInstance = ModuleInstance::factory()->create();
        $actionInstance = ActionInstance::factory()->create([
            'module_instance_id' => $moduleInstance->id
        ]);

        $this->assertInstanceOf(ModuleInstance::class, $actionInstance->moduleInstance);
        $this->assertModelEquals($moduleInstance, $actionInstance->moduleInstance);
    }

    /** @test */
    public function user_returns_a_user_with_the_correct_id()
    {
        $user = $this->newUser();
        $userRepository = $this->prophesize(User::class);
        $userRepository->getById($user->id())->shouldBeCalled()->willReturn($user);
        $this->instance(User::class, $userRepository->reveal());

        $actionInstance = ActionInstance::factory()->create(['user_id' => $user->id()]);
        $this->assertInstanceOf(\BristolSU\ControlDB\Models\User::class, $actionInstance->user());
        $this->assertModelEquals($user, $actionInstance->user());
    }

    /** @test */
    public function user_throws_an_exception_if_user_id_is_null()
    {
        $actionInstance = ActionInstance::factory()->create(['user_id' => null, 'id' => 2000]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Action Instance #2000 is not owned by a user');

        $actionInstance->user();
    }

    /** @test */
    public function user_id_is_automatically_added_on_creation()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $actionInstance = ActionInstance::factory()->create(['user_id' => null]);

        $this->assertNotNull($actionInstance->user_id);
        $this->assertEquals($user->id(), $actionInstance->user_id);
    }

    /** @test */
    public function user_id_is_not_overridden_if_given()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $actionInstance = ActionInstance::factory()->create(['user_id' => $user->id()]);


        $this->assertNotNull($actionInstance->user_id);
        $this->assertEquals($user->id(), $actionInstance->user_id);
    }

    /** @test */
    public function revisions_are_saved()
    {
        $user = $this->newUser();
        $this->beUser($user);

        $actionInstance = ActionInstance::factory()->create(['name' => 'OldName']);

        $actionInstance->name = 'NewName';
        $actionInstance->save();

        $this->assertEquals(1, $actionInstance->revisionHistory->count());
        $this->assertEquals($actionInstance->id, $actionInstance->revisionHistory->first()->revisionable_id);
        $this->assertEquals(ActionInstance::class, $actionInstance->revisionHistory->first()->revisionable_type);
        $this->assertEquals('name', $actionInstance->revisionHistory->first()->key);
        $this->assertEquals('OldName', $actionInstance->revisionHistory->first()->old_value);
        $this->assertEquals('NewName', $actionInstance->revisionHistory->first()->new_value);
    }

    /** @test */
    public function it_has_history()
    {
        $actionInstance = ActionInstance::factory()->create();
        $histories = ActionHistory::factory()->count(10)->create([
            'action_instance_id' => $actionInstance->id
        ]);
        ActionHistory::factory()->count(5)->create();

        $resolvedHistory = $actionInstance->history;
        $this->assertInstanceOf(Collection::class, $resolvedHistory);
        $this->assertContainsOnlyInstancesOf(ActionHistory::class, $resolvedHistory);
        foreach ($histories as $history) {
            $this->assertModelEquals($history, $resolvedHistory->shift());
        }
    }
}

class ActionInstanceDummyAction extends Action
{
    public function run(): ActionResponse
    {
        return ActionResponse::success();
    }

    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        return new Form();
    }

    public function __construct(array $data)
    {
    }
}

class ActionInstanceDummyEvent implements TriggerableEvent
{
    public function getFields(): array
    {
        return [
            'eventfield1' => 'EventFieldValue'
        ];
    }

    public static function getFieldMetaData(): array
    {
        return [
            'eventfield1' => [
                'label' => 'Event Field 1'
            ]
        ];
    }
}
