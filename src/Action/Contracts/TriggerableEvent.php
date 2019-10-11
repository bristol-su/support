<?php


namespace BristolSU\Support\Action\Contracts;


interface TriggerableEvent
{

    public function getFields(): array;

    public static function getFieldMetaData(): array;
    
}