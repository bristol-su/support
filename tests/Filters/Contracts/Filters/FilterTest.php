<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Tests\TestCase;

class FilterTest extends TestCase
{

    /** @test */
    public function toArray_returns_an_array_of_attributes(){
        $filter = new DummyFilter;
        
        $this->assertEquals([
            'alias' => 'alias1',
            'name' => 'name1',
            'description' => 'description1',
            'options' => ['option' => 'value1']
        ], $filter->toArray());
    }
    
}

class DummyFilter extends Filter
{

    /**
     * @inheritDoc
     */
    public function options(): array
    {
        return ['option' => 'value1'];
    }

    /**
     * @inheritDoc
     */
    public function hasModel(): bool
    {
        // TODO: Implement hasModel() method.
    }

    /**
     * @inheritDoc
     */
    public function setModel($model)
    {
        // TODO: Implement setModel() method.
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
        // TODO: Implement evaluate() method.
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'name1';
    }

    /**
     * @inheritDoc
     */
    public function description()
    {
        return 'description1';
    }

    /**
     * @inheritDoc
     */
    public function alias()
    {
        return 'alias1';
    }
}