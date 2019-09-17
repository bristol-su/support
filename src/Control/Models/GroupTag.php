<?php

namespace BristolSU\Support\Control\Models;

use BristolSU\Support\Control\Contracts\Models\GroupTag as GroupTagContract;

class GroupTag extends Model implements GroupTagContract
{

    public function name()
    {
        return $this->attributes['name'];
    }

    public function fullReference()
    {
        return $this->attributes['category']['reference'].'.'.$this->attributes['reference'];
    }

    public function id()
    {
        return $this->attributes['id'];
    }
}

