<?php

namespace BristolSU\Support\Action\Contracts;

use FormSchema\Schema\Form;

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
     * A form schema describing the settings the actions need
     * 
     * Any options here will be shown to the user on setup. The results will be passed into the construct of this class.
     * 
     * @return Form
     */
    public static function options(): Form;
    
}