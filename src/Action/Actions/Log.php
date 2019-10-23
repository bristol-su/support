<?php

namespace BristolSU\Support\Action\Actions;

use BristolSU\Support\Action\Contracts\Action;

/**
 * Class Log
 * @package BristolSU\Support\Action\Actions
 */
class Log implements Action
{

    /**
     * @var mixed|string
     */
    private $text = 'This will be written to the log';

    /**
     * Log constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->text = $data['text'];
    }

    public function handle()
    {
        \Illuminate\Support\Facades\Log::info($this->text);
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return [
            'text' => $this->text
        ];
    }

    /**
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