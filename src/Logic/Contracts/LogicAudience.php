<?php

namespace BristolSU\Support\Logic\Contracts;

use BristolSU\Support\Logic\Logic;

/**
 * Interface LogicAudience
 * @package BristolSU\Support\Logic\Contracts
 */
interface LogicAudience
{

    /**
     * @param Logic $logic
     * @return mixed
     */
    public function audience(Logic $logic);

}
