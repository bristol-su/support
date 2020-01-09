<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Represents an evaluation of a module instance
 */
interface Evaluation extends Arrayable, Jsonable
{

    /**
     * Set the visibility of the module instance
     * 
     * @param bool $visible Is the module instance visible?
     * @return void
     */
    public function setVisible(bool $visible);

    /**
     * Set if the module instance is mandatory
     * 
     * @param bool $mandatory Is the module instance mandatory
     * @return void
     */
    public function setMandatory(bool $mandatory);

    /**
     * Set if the module instance is active
     * 
     * @param bool $active Is the module instance active
     * @return void
     */
    public function setActive(bool $active);

    /**
     * Set the completion of the module instance
     * 
     * @param bool $complete Is the module instance complete
     * @return void
     */
    public function setComplete(bool $complete);

    /**
     * Get the visibility of the module instance
     * 
     * @return bool
     */
    public function visible(): bool;

    /**
     * Get if the module instance is mandatory
     * 
     * @return bool
     */
    public function mandatory(): bool;

    /**
     * Get if the module instance is active
     * 
     * @return bool
     */
    public function active(): bool;

    /**
     * Get if the module instance is complete
     * 
     * @return bool
     */
    public function complete(): bool;
}
