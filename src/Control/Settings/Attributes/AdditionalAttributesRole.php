<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\GlobalSetting;
use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class AdditionalAttributesRole extends GlobalSetting
{

    /**
     * The key for the setting
     *
     * @return string
     */
    public function key(): string
    {
        return 'control.data-fields.role';
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
     * @throws \Exception
     */
    public function fieldOptions(): Field
    {
        return \FormSchema\Generator\Field::input($this->inputName())->inputType('text')->getSchema();
    }

    /**
     * Return the validation rules for the setting.
     *
     * The key to use for the rules is data. You may also override the validator method to customise the validator further
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            $this->inputName() => 'optional'
        ];
    }
}
