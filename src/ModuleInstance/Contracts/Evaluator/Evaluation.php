<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface Evaluation
 * @package BristolSU\Support\ModuleInstance\Contracts\Evaluator
 */
interface Evaluation extends Arrayable, Jsonable
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
     * @param bool $complete
     * @return mixed
     */
    public function setComplete(bool $complete);

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

    /**
     * @return bool
     */
    public function complete(): bool;
}
