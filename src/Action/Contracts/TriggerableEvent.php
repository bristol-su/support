<?php


namespace BristolSU\Support\Action\Contracts;


/**
 * Interface TriggerableEvent
 * @package BristolSU\Support\Action\Contracts
 */
interface TriggerableEvent
{

    /**
     * @return array
     */
    public function getFields(): array;

    /**
     * @return array
     */
    public static function getFieldMetaData(): array;
    
}