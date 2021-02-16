<?php

namespace BristolSU\Support\Search;

trait Searchable
{
    use \Laravel\Scout\Searchable;

    abstract public function toSearchableArray();

}
