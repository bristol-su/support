<?php

namespace BristolSU\Support\Action\Actions;

use BristolSU\Support\Action\Contracts\Action;

/**
 * Logging Action.
 * 
 * Logs a message to the PHP log.
 */
class Log implements Action
{

    /**
     * Message to be written to the log
     * 
     * @var mixed|string
     */
    private $text = 'This will be written to the log';

    /**
     * Initialise the Log action with the text
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->text = $data['text'];
    }

    /**
     * Handle logging the message to the log
     */
    public function handle()
    {
        \Illuminate\Support\Facades\Log::info($this->text);
    }

    /**
     * Returns the log attributes needed.
     * 
     * @return array
     */
    public function getFields(): array
    {
        return [
            'text' => $this->text
        ];
    }

    /**
     * Returns the log attribute meta data
     * 
     * @return array
     */
    public static function getFieldMetaData(): array
    {
        return [
            'text' => [
                'label' => 'Message',
                'helptext' => 'The message to save to the log'
            ]
        ];
    }
}