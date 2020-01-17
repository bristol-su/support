<?php

namespace BristolSU\Support\Action\Contracts;

/**
 * Implementing this interface will create an Action.
 */
interface Action
{

    public function __construct(array $data);

    /**
     * Do the action.
     * 
     * If this method is called, you can assume the data requested in the getFields function has been passed
     * into the constructor of the class, so should be used in the handle method.
     * 
     * @return mixed
     */
    public function handle();

    /**
     * Get the fields that the action needs.
     * 
     * If the action needs any settings, such as a message, you should return them here along with a default.
     * e.g. [
     *      'to' => 'example@example.com',
     *      'subject' => 'Default Subject',
     *      'message' => 'Default message'
     * ]
     * @return array
     */
    public function getFields(): array;

    /**
     * Register metadata about the fields the action needs.
     * 
     * For each action field requested in getFields, pass in a label and a helptext.
     * e.g. [
     *      'to' => [
     *          'label' => 'To',
     *          'helptext' => 'Email address of the recipient'
     *          ], 
     *      ...
     * ]
     * @return array
     */
    public static function getFieldMetaData(): array;
    
}