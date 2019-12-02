<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;

/**
 * Class Evaluation
 * @package BristolSU\Support\ModuleInstance\Evaluator
 */
class Evaluation implements EvaluationContract
{
    /**
     * @var bool
     */
    private $active = false;

    /**
     * @var bool
     */
    private $visible = false;

    /**
     * @var bool
     */
    private $mandatory = false;

    /**
     * @var bool
     */
    private $complete = false;
    
    /**
     * @return bool
     */
    public function active(): bool
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function mandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * @return bool
     */
    public function complete(): bool
    {
        return $this->complete;
    }

    /**
     * @param bool $active
     * @return mixed|void
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * @param bool $mandatory
     * @return mixed|void
     */
    public function setMandatory(bool $mandatory)
    {
        $this->mandatory = $mandatory;
    }

    /**
     * @param bool $visible
     * @return mixed|void
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;
    }

    /**
     * @param bool $complete
     * @return mixed|void
     */
    public function setComplete(bool $complete)
    {
        $this->complete = $complete;
    }

    /**
     * @return bool
     */
    public function visible(): bool
    {
        return $this->visible;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'active' => $this->active(),
            'visible' => $this->visible(),
            'mandatory' => $this->mandatory(),
            'complete' => $this->complete()
        ];
    }


    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}
