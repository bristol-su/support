<?php

namespace BristolSU\Support\Action;

use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Support\Collection;

class ActionInstanceRepository  implements \BristolSU\Support\Action\Contracts\ActionInstanceRepository
{

    /**
     * @inheritDoc
     */
    public function forEvent(int $moduleInstanceId, string $event): Collection
    {
        return ActionInstance::where('module_instance_id', $moduleInstanceId)
            ->where('event', $event)
            ->get();
    }
}