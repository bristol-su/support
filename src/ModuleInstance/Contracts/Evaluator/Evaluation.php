<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface Evaluation
 * @package BristolSU\Support\ModuleInstance\Contracts\Evaluator
 */
interface Evaluation extends Arrayable
{

    /**
     * @param bool $visible
     * @return mixed
     */
    public function setVisible(bool $visible);

    /**
     * @param bool $mandatory
     * @return mixed
     */
    public function setMandatory(bool $mandatory);

    /**
     * @param bool $active
     * @return mixed
     */
    public function setActive(bool $active);

    /**
     * @return bool
     */
    public function visible(): bool;

    /**
     * @return bool
     */
    public function mandatory(): bool;

    /**
     * @return bool
     */
    public function active(): bool;

}
