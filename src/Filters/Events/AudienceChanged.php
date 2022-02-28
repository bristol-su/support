<?php

namespace BristolSU\Support\Filters\Events;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Filters\FilterInstance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudienceChanged
{
    use Dispatchable, SerializesModels;

    /**
     * @var array|FilterInstance[]
     */
    public array $filterInstances;

    public User|Group|Role|null $model;

    /**
     * Create a new event instance.
     *
     * @param array $filterInstances Array of filter instances that have changed
     * @param User|Group|Role|null $model
     */
    public function __construct(array $filterInstances, User|Group|Role|null $model = null)
    {
        $this->filterInstances = $filterInstances;
        $this->model = $model;
    }
}
