<?php

namespace BristolSU\Support\Action\Contracts;

use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\History\HasHistory;
use BristolSU\Support\Action\History\RecordsHistory;
use Exception;
use FormSchema\Schema\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Represents a queueable action.
 */
abstract class Action implements ShouldQueue, RecordsHistory
{
    use Dispatchable;
    use Queueable;
    use HasHistory;

    /**
     * @var array
     */
    private $data;

    /**
     * @var ActionResponse|null
     */
    private $response;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * A form schema describing the settings the actions need.
     *
     * Any options here will be shown to the user on setup. The results will be passed into the construct of this class.
     *
     * @return Form
     */
    abstract public static function options(): Form;

    public function handle()
    {
        try {
            $this->response = app()->call([$this, 'run']);
        } catch (Exception $e) {
            $this->response = ActionResponse::failure(($e->getMessage() === '' ? 'An error was thrown during processing' : $e->getMessage()));
        }
        if ($this instanceof RecordsHistory) {
            $this->saveHistory($this->getResponse());
        }
    }

    /**
     * Get the response as given by the action after running.
     *
     * @return ActionResponse|null
     */
    public function getResponse(): ?ActionResponse
    {
        return $this->response;
    }

    /**
     * Get a piece of information from the settings.
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function option(string $key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * Get all setting values.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Run the action.
     *
     * @return ActionResponse
     */
    abstract public function run(): ActionResponse;
}
