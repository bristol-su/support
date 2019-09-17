<?php


namespace BristolSU\Support\Filters\Contracts;


interface FilterInstance
{
    public function name();

    public function alias();

    public function settings();
}
