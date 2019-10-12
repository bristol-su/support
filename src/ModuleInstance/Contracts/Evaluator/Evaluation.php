<?php

namespace BristolSU\Support\ModuleInstance\Contracts\Evaluator;

use Illuminate\Contracts\Support\Arrayable;

interface Evaluation extends Arrayable
{

    public function setVisible(bool $visible);

    public function setMandatory(bool $mandatory);

    public function setActive(bool $active);

    public function visible(): bool;

    public function mandatory(): bool;

    public function active(): bool;

}
