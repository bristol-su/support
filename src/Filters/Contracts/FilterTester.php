<?php


namespace BristolSU\Support\Filters\Contracts;


interface FilterTester
{
    public function evaluate(FilterInstance $filterInstance): bool;
}
