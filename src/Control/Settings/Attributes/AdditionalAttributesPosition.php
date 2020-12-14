<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\GlobalSetting;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

class AdditionalAttributesPosition extends GlobalSetting
{

    /**
     * The key for the setting
     *
     * @return string
     */
    public function key(): string
    {
        return 'control.data-fields.position';
    }

    /**
     * The default value of the setting
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return [];
    }

    /**
     * The field schema to show the user when editing the value
     *
     * @return Field
     */
    public function fieldOptions(): Field
    {
        // TODO Fill in field options
    }

    /**
     * A validator to validate any new values
     *
     * @param mixed $value The new value
     * @return Validator
     */
    public function validator($value): Validator
    {
        // TODO: Implement validator() method.
    }
}
