<?php


namespace BristolSU\Support\Action\Contracts;


/**
 * Interface TriggerableEvent
 */
interface TriggerableEvent
{

    /**
     * Get the fields that the event registers.
     *
     * If the event has parameters which should be used in the framework, return them here.
     * 
     * e.g. [
     *      'user_id' => 1,
     *      'comment_id' => 4,
     *      'post_id' => 3
     * ]
     * @return array
     */
    public function getFields(): array;

    /**
     * Register metadata about the fields the event supplies.
     *
     * For each field returned in getFields, pass in a label and a helptext.
     * e.g. [
     *      'user_id' => [
     *          'label' => 'User ID',
     *          'helptext' => 'The ID of the user who posted the comment.'
     *          ],
     *      ...
     * ]
     * @return array
     */
    public static function getFieldMetaData(): array;
    
}