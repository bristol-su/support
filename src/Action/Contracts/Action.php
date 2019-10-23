<?php


namespace BristolSU\Support\Action\Contracts;


/**
 * Interface Action
 * @package BristolSU\Support\Action\Contracts
 */
interface Action
{

    public function handle();

    /**
     * @return array
     */
    public function getFields(): array;

    /**
     * @return array
     */
    public static function getFieldMetaData(): array;
    
}