<?php

namespace BristolSU\Support\Action\Actions;

use BristolSU\Support\Action\Contracts\Action;

class Log implements Action
{

    private $text = 'This will be written to the log';

    public function __construct(array $data = [])
    {
        $this->text = $data['text'];
    }

    public function handle()
    {
        \Illuminate\Support\Facades\Log::info($this->text);
    }

    public function getFields(): array
    {
        return [
            'text' => $this->text
        ];
    }

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