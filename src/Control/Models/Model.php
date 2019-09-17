<?php

namespace BristolSU\Support\Control\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Model implements Arrayable, Jsonable
{
    protected $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($name)
    {
        if(!is_array($this->attributes)) {
            dd($this->attributes, $name);
        }
        if(isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }

}
