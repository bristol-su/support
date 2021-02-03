<?php

namespace BristolSU\Support\Progress;

class Progress
{
    /**
     * The ID of the activity the progress is for.
     *
     * @var integer
     */
    private $activityId;

    /**
     * The ID of the activity instance the progress is for.
     *
     * @var integer
     */
    private $activityInstanceId;

    /**
     * The time at which the progress snapshot was taken.
     *
     * @var \DateTime
     */
    private $timestamp;

    /**
     * Whether the progress is complete or not.
     *
     * @var boolean
     */
    private $complete;

    /**
     * The percentage marked as complete.
     *
     * @var float
     */
    private $percentage;

    /**
     * @var array|ModuleInstanceProgress[]
     */
    private $modules = [];

    /**
     * Get the activity ID.
     *
     * @return int
     */
    public function getActivityId(): int
    {
        return $this->activityId;
    }

    /**
     * Set the Activity ID.
     *
     * @param int $activityId
     */
    public function setActivityId(int $activityId): void
    {
        $this->activityId = $activityId;
    }

    /**
     * Get the activity instance ID.
     *
     * @return int
     */
    public function getActivityInstanceId(): int
    {
        return $this->activityInstanceId;
    }

    /**
     * Get the activity instance ID.
     *
     * @param int $activityInstanceId
     */
    public function setActivityInstanceId(int $activityInstanceId): void
    {
        $this->activityInstanceId = $activityInstanceId;
    }

    /**
     * Get the timestamp of the progress snapshot.
     *
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * Set the timestamp of the progress snapshot.
     *
     * @param \DateTime $timestamp
     */
    public function setTimestamp(\DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Is the activity marked as complete?
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->complete;
    }

    /**
     * Set the progress completion status.
     *
     * @param bool $complete
     */
    public function setComplete(bool $complete): void
    {
        $this->complete = $complete;
    }

    /**
     * Get the percentage completion of the activity.
     *
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }

    /**
     * Set the percentage completion of the activity.
     *
     * @param float $percentage
     */
    public function setPercentage(float $percentage): void
    {
        $this->percentage = $percentage;
    }

    /**
     * Get all module instance progresses.
     *
     * @return array|ModuleInstanceProgress[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Add a module instance progress to this progress snapshot.
     *
     * @param ModuleInstanceProgress $moduleInstanceProgress
     */
    public function pushModule(ModuleInstanceProgress $moduleInstanceProgress): void
    {
        $this->modules[] = $moduleInstanceProgress;
    }
    
    /**
     * Create a new progress model.
     *
     * @param int $activityId
     * @param int $activityInstanceId
     * @param \DateTime $timestamp
     * @param bool $complete
     * @param float $percentage
     * @return static
     */
    public static function create(
        int $activityId,
        int $activityInstanceId,
        \DateTime $timestamp,
        bool $complete,
        float $percentage
    ): self {
        $progress = new self();
        $progress->setActivityId($activityId);
        $progress->setActivityInstanceId($activityInstanceId);
        $progress->setTimestamp($timestamp);
        $progress->setComplete($complete);
        $progress->setPercentage($percentage);

        return $progress;
    }
}
