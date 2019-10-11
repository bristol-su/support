<?php


namespace BristolSU\Support\Action\Contracts;


interface Action
{

    public function handle();
    
    public function getFields(): array;

    public static function getFieldMetaData(): array;
    
}