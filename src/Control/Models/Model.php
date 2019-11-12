<?php

namespace BristolSU\Support\Control\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class Model
 * @package BristolSU\Support\Control\Models
 */
class Model implements Arrayable, Jsonable
{
    /**
     * @var array
     */
    protected $attributes;

    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $name
     * @return mixed|null
     * @throws \Exception
     */
    public function __get($name)
    {
        if(!is_array($this->attributes)) {
            throw new \Exception('Attributes not found for the given model');
        }
        if(isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }

}
