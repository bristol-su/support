<?php


namespace BristolSU\Support\Progress;


class ModuleInstanceProgress
{

    /**
     * The ID of the module instance
     * 
     * @var int 
     */
    private int $moduleInstanceId;

    /**
     * Whether the module instance is mandatory for anyone in the group
     * 
     * @var bool 
     */
    private bool $mandatory;

    /**
     * Whether the module instance is complete
     * 
     * @var bool 
     */
    private bool $complete;

    /**
     * The percentage completion of the module instance
     * 
     * @var float 
     */
    private float $percentage;

    /**
     * Marks if the module is active for anyone in the activity instance
     * 
     * @var bool
     */
    private bool $active;

    /**
     * Marks if the module is visible for anyone in the activity instance
     * @var bool 
     */
    private bool $visible;

    /**
     * Get the ID of the module instance this progress is related to
     * 
     * @return int
     */
    public function getModuleInstanceId(): int
    {
        return $this->moduleInstanceId;
    }

    /**
     * Set the ID of the module instance this progress is related to
     * 
     * @param int $moduleInstanceId
     */
    public function setModuleInstanceId(int $moduleInstanceId): void
    {
        $this->moduleInstanceId = $moduleInstanceId;
    }

    /**
     * Get if this module instance is mandatory for anyone in the activity instance
     * 
     * @return bool
     */
    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * Set if this module instance is mandatory for anyone in the activity instance
     * 
     * @param bool $mandatory
     */
    public function setMandatory(bool $mandatory): void
    {
        $this->mandatory = $mandatory;
    }

    /**
     * Get if the module instance has met the completion conditions.
     * 
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->complete;
    }

    /**
     * Set if the module instance has met the completion conditions
     * 
     * @param bool $complete
     */
    public function setComplete(bool $complete): void
    {
        $this->complete = $complete;
    }

    /**
     * Get the module instance completion percentage
     * 
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }

    /**
     * Set the module instance completion percentage
     * 
     * @param float $percentage
     */
    public function setPercentage(float $percentage): void
    {
        $this->percentage = $percentage;
    }

    /**
     * Get if the module instance is active for anyone in the activity instance
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Set if the module instance is active for anyone in the activity instance
     * 
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * Get if the module instance is visible for anyone in the activity instance
     * 
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * Set if the module instance is visible for anyone in the activity instance
     * 
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    /**
     * Create a new module instance progress model
     * 
     * @param int $moduleInstanceId
     * @param bool $mandatory
     * @param bool $complete
     * @param float $percentage
     * @param bool $active
     * @param bool $visible
     * 
     * @return static
     */
    public static function create(
        int $moduleInstanceId,
        bool $mandatory,
        bool $complete,
        float $percentage,
        bool $active,
        bool $visible
    ): self 
    {
        $moduleInstanceProgress = new static();
        $moduleInstanceProgress->setModuleInstanceId($moduleInstanceId);
        $moduleInstanceProgress->setMandatory($mandatory);
        $moduleInstanceProgress->setComplete($complete);
        $moduleInstanceProgress->setPercentage($percentage);
        $moduleInstanceProgress->setActive($active);
        $moduleInstanceProgress->setVisible($visible);
        return $moduleInstanceProgress;
    }
    
    
}