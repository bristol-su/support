<?php

namespace BristolSU\Support\Filters\Events;

use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\Support\Filters\FilterInstance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AudienceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public FilterInstance $filterInstance;
    public Group|Role|User|null $model;

    /**
     * Create a new event instance.
     *
     * @param FilterInstance $filterInstance
     * @param array $conditions
     */
    public function __construct(FilterInstance $filterInstance, User|Group|Role|null $model = null)
    {
        $this->filterInstance = $filterInstance;
        $this->model = $model;
    }

}
