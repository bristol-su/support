<?php


namespace BristolSU\Support\DataPlatform\Models;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class Model
 * @package BristolSU\Support\DataPlatform\Models
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
     */
    public function __get($name)
    {
        if(isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
