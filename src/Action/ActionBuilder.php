<?php


namespace BristolSU\Support\Action;


use BristolSU\Support\Action\Contracts\Action;
use BristolSU\Support\Action\Contracts\ActionBuilder as ActionBuilderContract;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class ActionBuilder
 * @package BristolSU\Support\Action
 */
class ActionBuilder implements ActionBuilderContract
{
    /**
     * @var Application
     */
    private $app;

    /**
     * ActionBuilder constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param ActionInstance $actionInstance
     * @param array $data
     * @return Action
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function build(ActionInstance $actionInstance, array $data = []): Action
    {
        return $this->app->make($actionInstance->action, [
            'data' => $this->mapFields($actionInstance->actionInstanceFields, $data)
        ]);
    }

    /**
     * @param $fields
     * @param array $data
     * @return array
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
