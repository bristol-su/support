<?php

namespace BristolSU\Support\Tests\Filters;

use BristolSU\Support\Filters\Contracts\FilterManager;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\FilterManagerEventRegistrationDecorator;
use BristolSU\Support\Filters\Listeners\RefreshFilterResults;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Generator\Form;
use FormSchema\Schema\Form as FormSchema;
use Illuminate\Support\Facades\Event;
use Prophecy\Argument;

class FilterManagerEventRegistrationDecoratorTest extends TestCase
{

    /** @test */
    public function getAll_forwards_the_call_to_the_base_instance(){
        $baseInstance = $this->prophesize(FilterManager::class);
        $baseInstance->getAll()->shouldBeCalled()->willReturn([]);

        $decorator = new FilterManagerEventRegistrationDecorator($baseInstance->reveal());
        $this->assertEquals([], $decorator->getAll());
    }

    /** @test */
    public function getClassFromAlias_forwards_the_call_to_the_base_instance(){
        $baseInstance = $this->prophesize(FilterManager::class);
        $baseInstance->getClassFromAlias('filter-alias')->shouldBeCalled()->willReturn('FilterClass');

        $decorator = new FilterManagerEventRegistrationDecorator($baseInstance->reveal());
        $this->assertEquals('FilterClass', $decorator->getClassFromAlias('filter-alias'));
    }

    /** @test */
    public function register_registers_the_filter_then_binds_events(){
        Event::fake();

        $baseInstance = $this->prophesize(FilterManager::class);
        $baseInstance->register('filter-alias', FilterManagerEventRegistrationDecoratorTestDummyFilter::class)
            ->shouldBeCalled();

        $decorator = new FilterManagerEventRegistrationDecorator($baseInstance->reveal());
        $decorator->register('filter-alias', FilterManagerEventRegistrationDecoratorTestDummyFilter::class);

        Event::assertListening('Event1', [RefreshFilterResults::class, 'handle']);
        Event::assertListening('Event2', [RefreshFilterResults::class, 'handle']);
        Event::assertListening('Event3', [RefreshFilterResults::class, 'handle']);
    }
}

class FilterManagerEventRegistrationDecoratorTestDummyFilter extends UserFilter
{

    public static function clearOn(): array
    {
        return [
            'Event1' => fn($event) => 1,
            'Event2' => fn($event) => 2,
            'Event3' => fn($event) => false,
        ];
    }

    public function options(): FormSchema
    {
        return Form::make()->form();
    }

    public function evaluate(array $settings): bool
    {
        return true;
    }

    public function name()
    {
        return '';
    }

    public function description()
    {
        return '';
    }

    public function alias()
    {
        return '';
    }
}
