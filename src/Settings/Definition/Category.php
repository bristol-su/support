<?php

namespace BristolSU\Support\Settings\Definition;

abstract class Category
{

    abstract public function name(): string;

    abstract public function definition(): string;

    abstract public function icon(): string;

}
