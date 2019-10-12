<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;

class Evaluation implements EvaluationContract
{
    private $active = false;

    private $visible = false;

    private $mandatory = false;

    public function active(): bool
    {
        return $this->active;
    }

    public function mandatory(): bool
    {
        return $this->mandatory;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function setMandatory(bool $mandatory)
    {
        $this->mandatory = $mandatory;
    }

    public function setVisible(bool $visible)
    {
        $this->visible = $visible;
    }

    public function visible(): bool
    {
        return $this->visible;
    }

    public function toArray()
    {
        return [
            'active' => $this->active(),
            'visible' => $this->visible(),
            'mandatory' => $this->mandatory(),
        ];
    }


}
