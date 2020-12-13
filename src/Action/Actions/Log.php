<?php

namespace BristolSU\Support\Action\Actions;

use BristolSU\Support\Action\ActionResponse;
use BristolSU\Support\Action\Contracts\Action;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;

/**
 * Logging Action.
 *
 * Logs a message to the PHP log.
 */
class Log extends Action
{

    /**
     * Handle logging the message to the log
     */
    public function run(): ActionResponse
    {
        try {
            \Illuminate\Support\Facades\Log::info($this->option('text'));
        } catch (\Exception $e) {
            return ActionResponse::failure(($e->getMessage() === '' ? 'Could not log the text' : $e->getMessage()));
        }
        return ActionResponse::success('Text saved to the log file');
    }

    /**
     * @inheritDoc
     */
    public static function options(): Form
    {
        return \FormSchema\Generator\Form::make()->withField(
            Field::input('text')->inputType('text')->label('Message')
                ->required(true)->default('')->hint('The message to save to the log')
                ->help('When triggered, this message will be saved to the php logs')
        )->getSchema();
    }
}
