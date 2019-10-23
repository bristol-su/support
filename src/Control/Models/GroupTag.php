<?php

namespace BristolSU\Support\Control\Models;

use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagContract;

/**
 * Class GroupTag
 * @package BristolSU\Support\Control\Models
 */
class GroupTag extends Model implements GroupTagContract
{

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->attributes['name'];
    }

    /**
     * @return string
     */
    public function fullReference()
    {
        return $this->attributes['category']['reference'].'.'.$this->attributes['reference'];
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->attributes['id'];
    }
}

