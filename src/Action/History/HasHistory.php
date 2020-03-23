<?php

namespace BristolSU\Support\Action\History;

use BristolSU\Support\Action\ActionResponse;
use Exception;
use Illuminate\Http\Response;

trait HasHistory
{

    /**
     * ID to use for action instances
     *
     * @var int
     */
    private $actionInstanceId;

    /**
     * Data from the event
     *
     * @var
     */
    private $eventFields;

    /**
     * Data from the settings passed to the action
     *
     * @var
     */
    private $settings;

    /**
     * Set the action instance for use in the action history records
     *
     * @param int $actionInstanceId
     */
    public function setActionInstanceId(int $actionInstanceId): void
    {
        $this->actionInstanceId = $actionInstanceId;
    }

    /**
     * Set the fields given by the event
     *
     * @param array $eventFields
     */
    public function setEventFields(array $eventFields): void
    {
        $this->eventFields = $eventFields;
    }

    /**
     * Set the data passed to the action
     *
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * Save the history of the action run
     *
     * @param ActionResponse $response Response of the run
     * @throws Exception If the action instance ID is not given
     */
    public function saveHistory(ActionResponse $response): void
    {
        if ($this->actionInstanceId === null){
            throw new Exception('The action instance ID must not be null');
        }

        ActionHistory::create([
            'action_instance_id' => $this->actionInstanceId,
            'event_fields' => ($this->eventFields ?? []),
            'settings' => ($this->settings ?? []),
            'success' => $response->getSuccess(),
            'message' => $response->getMessage()
        ]);
    }

}