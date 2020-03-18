<?php

namespace BristolSU\Support\Action\Actions;

use BristolSU\Support\Action\Contracts\Action;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;

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
     * @inheritDoc
     */
    public static function options(): Form
    {
        return \FormSchema\Generator\Form::make()->withField(
            \FormSchema\Generator\Field::input('text')->inputType('text')->label('Message')
                ->required(true)->default('')->hint('The message to save to the log')
                ->help('When triggered, this message will be saved to the php logs')
        )->getSchema();
    }
}