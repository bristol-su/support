<?php

namespace BristolSU\Support\Action\History;

use BristolSU\Support\Action\ActionResponse;

interface RecordsHistory
{

    /**
     * Set the action instance for use in the action history records
     *
     * @param int $actionInstanceId
     */
    public function setActionInstanceId(int $actionInstanceId): void;

    /**
     * Set the fields given by the event
     *
     * @param array $eventFields
     */
    public function setEventFields(array $eventFields): void;

    /**
     * Set the data passed to the action
     *
     * @param array $settings
     */
    public function setSettings(array $settings): void;

    /**
     * Save the history of the action run
     *
     * @param ActionResponse $response Response of the run
     */
    public function saveHistory(ActionResponse $response): void;
    
}