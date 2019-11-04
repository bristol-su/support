<?php


namespace BristolSU\Support\Filters\Contracts\Filters;


use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Filter
 * @package BristolSU\Support\Filters\Contracts\Filters
 */
abstract class Filter implements Arrayable
{

    /**
     * Get possible options as an array
     *
     * @return array
     */
    abstract public function options(): array;

    /**
     * @return bool
     */
    abstract public function hasModel(): bool;

    /**
     * @param $model
     * @return mixed
     */
    abstract public function setModel($model);

    /**
     * Test if the filter passes
     *
     * @param string $settings Key of the chosen option
     *
     * @return bool
     */
    abstract public function evaluate($settings): bool;

    /**
     * @return mixed
     */
    abstract public function name();

    /**
     * @return mixed
     */
    abstract public function description();

    abstract public function for();

    /**
     * @return mixed
     */
    abstract public function alias();

    /**
     * @param $settings
     * @return mixed
     */
    abstract public function audience($settings);

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'alias' => $this->alias(),
            'name' => $this->name(),
            'description' => $this->description(),
            'for' => $this->for(),
            'options' => $this->options()
        ];
    }


}
