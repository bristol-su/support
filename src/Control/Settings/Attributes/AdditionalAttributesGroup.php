<?php

namespace BristolSU\Support\Control\Settings\Attributes;

use BristolSU\Support\Settings\Definition\GlobalSetting;
use FormSchema\Schema\Field;

class AdditionalAttributesGroup extends GlobalSetting
{
    /**
     * The key for the setting.
     *
     * @return string
     */
    public function key(): string
    {
        return 'control.data-fields.group';
    }

    /**
     * The default value of the setting.
     *
     * @return mixed
     */
    public function defaultValue()
    {
        return [];
    }

    /**
     * The field schema to show the user when editing the value.
     *
     * @throws \Exception
     * @return Field
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
