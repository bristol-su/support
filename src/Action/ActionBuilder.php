<?php


namespace BristolSU\Support\Action;

use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use Illuminate\Contracts\Container\Container;

/**
 * Builds an action class using the Laravel container.
 */
class ActionBuilder implements ActionBuilderContract
{
    /**
     * Holds the container
     * 
     * @var Container
     */
    private $app;

    /**
     * Initialise the Action Builder
     * 
     * @param Container $app Container to resolve the actions from.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Resolve an action out of the container.
     * 
     * Fields from the ActionInstance will be mapped to the action field, then passed to the action to resolve with.
     * 
     * @param ActionInstance $actionInstance ActionInstance which needs to be built
     * @param array $data Event fields
     * @return Action An action storing the data
     * 
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function build(ActionInstance $actionInstance, array $data = []): Action
    {
        return $this->app->make($actionInstance->action, [
            'data' => $this->mapFields($actionInstance->actionInstanceFields, $data)
        ]);
    }

    /**
     * For each action field, retrieve and return the action field from the event field
     * 
     * @param ActionInstanceField[] $fields ActionInstanceFields for the action instance. 
     * @param array $data Event field data
     * 
     * @return array Action field data
     */
    private function mapFields($fields, array $data)
    {
        $actionFields = [];
        foreach ($fields as $field) {
            $actionFields[$field->action_field] = $data[$field->event_field];
        }
        return $actionFields;
    }
}
