<?php


namespace BristolSU\Support\Tests\Control\Models;


use BristolSU\Support\Control\Models\Model;
use BristolSU\Support\Testing\TestCase;

class ModelTest extends TestCase
{

    /** @test */
    public function it_returns_an_attribute_if_called_as_a_property(){
        $model = new Model(['attr' => 'val']);
        $this->assertEquals('val', $model->attr);
    }

    /** @test */
    public function it_returns_null_if_attribute_not_found(){
        $model = new Model(['attr' => 'val']);
        $this->assertNull($model->anotherAttr);
    }

    /** @test */
    public function it_returns_all_attributes_as_an_array(){
        $parameters = [
            'foo' => 'bar',
            'baz' => 'qui'
        ];
        $model = new Model($parameters);
        $this->assertEquals($parameters, $model->toArray());
    }

    /** @test */
    public function toJson_returns_attributes_as_json(){
        $parameters = [
            'foo' => 'bar',
            'baz' => 'qui'
        ];
        $model = new Model($parameters);
        $this->assertEquals('{"foo":"bar","baz":"qui"}', $model->toJson());
    }

    /** @test */
    public function __toString_returns_attributes_as_json(){
        $parameters = [
            'foo' => 'bar',
            'baz' => 'qui'
        ];
        $model = new Model($parameters);
        $this->assertEquals('{"foo":"bar","baz":"qui"}', (string)$model);
    }

}
