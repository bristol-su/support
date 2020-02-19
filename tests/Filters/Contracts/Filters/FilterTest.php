<?php

namespace BristolSU\Support\Tests\Filters\Contracts\Filters;

use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Tests\TestCase;
use FormSchema\Schema\Form;

class FilterTest extends TestCase
{

    /** @test */
    public function toArray_returns_an_array_of_attributes(){
        $filter = new DummyFilter;
        
        $this->assertEquals([
            'alias' => 'alias1',
            'name' => 'name1',
            'description' => 'description1',
            'options' => []
        ], $filter->toArray());
    }
    
}

class DummyFilter extends Filter
{

    /**
     * @inheritDoc
     */
    public function options(): Form
    {
        return new Form();
    }

    /**
     * @inheritDoc
     */
    public function hasModel(): bool
    {
    }

    /**
     * @inheritDoc
     */
    public function setModel($model)
    {
    }

    /**
     * @inheritDoc
     */
    public function evaluate($settings): bool
    {
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

    /**
     * @inheritDoc
     */
    public function model()
    {
    }
}