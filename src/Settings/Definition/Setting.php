<?php


namespace BristolSU\Support\Settings\Definition;


use FormSchema\Schema\Field;
use Illuminate\Contracts\Validation\Validator;

interface Setting
{

    /**
     * The key for the setting
     *
     * @return string
     */
    public function key(): string;

    /**
     * The default value of the setting
     *
     * @return mixed
     */
    public function defaultValue();

    /**
     * The field schema to show the user when editing the value
     *
     * @return Field
     */
    public function fieldOptions(): Field;

    /**
     * A validator to validate any new values
     *
     * @param mixed $value The new value
     * @return Validator
     */
    public function validator($value): Validator;

    /**
     * Should the setting value be encrypted?
     *
     * @return bool
     */
    public function shouldEncrypt(): bool;
}
