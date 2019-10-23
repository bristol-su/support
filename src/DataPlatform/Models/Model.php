<?php


namespace BristolSU\Support\DataPlatform\Models;


/**
 * Class Model
 * @package BristolSU\Support\DataPlatform\Models
 */
class Model
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
}
