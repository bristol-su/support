<?php

namespace BristolSU\Support\Action;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ActionInstanceRepository implements \BristolSU\Support\Action\Contracts\ActionInstanceRepository
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

    /**
     * Get all action instances for a given module instance
     *
     * @param int $moduleInstanceId ID of the module instance to get the action instances from
     * @return Collection
     */
    public function forModuleInstance(int $moduleInstanceId): Collection
    {
        return ActionInstance::where('module_instance_id', $moduleInstanceId)->get();
    }

    /**
     * Get an action instance by ID
     *
     * @param int $id
     * @return ActionInstance
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): ActionInstance
    {
        return ActionInstance::findOrFail($id);
    }

    /**
     * Get all action instances registered
     * @return Collection
     */
    public function all(): Collection
    {
        return ActionInstance::all();
    }

    /**
     * Update an action instance
     *
     * @param int $id
     * @param array $attributes
     * @return ActionInstance
     *
     * @throws ModelNotFoundException
     */
    public function update(int $id, array $attributes): ActionInstance
    {
        $actionInstance = $this->getById($id);
        $actionInstance->fill($attributes);
        $actionInstance->save();
        return $actionInstance;
    }

    /**
     * Delete an action instance
     *
     * @param int $id
     * @return void
     *
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $actionInstance = $this->getById($id);
        $actionInstance->delete();
    }


}
