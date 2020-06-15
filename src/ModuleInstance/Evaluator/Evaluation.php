<?php


namespace BristolSU\Support\ModuleInstance\Evaluator;


use BristolSU\Support\ModuleInstance\Contracts\Evaluator\Evaluation as EvaluationContract;

/**
 * Represents an evaluation of a module instance
 */
class Evaluation implements EvaluationContract
{
    /**
     * Is the module instance active?
     * 
     * @var bool
     */
    private $active = false;

    /**
     * Is the module instance visible?
     * 
     * @var bool
     */
    private $visible = false;

    /**
     * Is the module instance mandatory?
     * 
     * @var bool
     */
    private $mandatory = false;

    /**
     * Is the module instance complete?
     * 
     * @var bool
     */
    private $complete = false;

    /**
     * The percentage completion of the module instance
     * 
     * @var int 
     */
    private $percentage = 0;
    
    /**
     * Is the module instance active?
     * 
     * @return bool
     */
    public function active(): bool
    {
        return $this->active;
    }

    /**
     * Is the module instance mandatory?
     * 
     * @return bool
     */
    public function mandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * Is the module instance complete?
     * 
     * @return bool
     */
    public function complete(): bool
    {
        return $this->complete;
    }

    /**
     * Set the active status of the module instance evaluation
     * 
     * @param bool $active New active status of the module instance evaluation
     * @return void
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * Set the mandatory status of the module instance evaluation
     * 
     * @param bool $mandatory Mandatory status
     * @return void
     */
    public function setMandatory(bool $mandatory)
    {
        $this->mandatory = $mandatory;
    }

    /**
     * Set the visible status of the module instance evaluation
     * 
     * @param bool $visible Visible status
     * @return void
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;
    }

    /**
     * Set the complete status of the module instance evaluation 
     * 
     * @param bool $complete Complete status
     * @return void
     */
    public function setComplete(bool $complete)
    {
        $this->complete = $complete;
    }

    /**
     * Is the module instance visible?
     * 
     * @return bool
     */
    public function visible(): bool
    {
        return $this->visible;
    }

    /**
     * Get the percentage completion of the module
     *
     * @return float
     */
    public function percentage(): float
    {
        return $this->percentage;
    }

    /**
     * Set the percentage completion of the module
     *
     * @param float $percentage
     */
    public function setPercentage(float $percentage)
    {
        $this->percentage = $percentage;
    }


    /**
     * Cast the representation to an array
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'active' => $this->active(),
            'visible' => $this->visible(),
            'mandatory' => $this->mandatory(),
            'complete' => $this->complete(),
            'percentage' => $this->percentage()
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

    /**
     * Convert the object to a JSON representation
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
