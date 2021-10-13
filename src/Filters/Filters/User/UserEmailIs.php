<?php

namespace BristolSU\Support\Filters\Filters\User;

use BristolSU\ControlDB\Events\DataUser\DataUserUpdated;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;
use BristolSU\ControlDB\Events\User\UserUpdated;

/**
 * Does the user have the given email?
 */
class UserEmailIs extends UserFilter
{
    /**
     * Get possible options as an array.
     *
     * You should return a form schema which represents the available options for the filter
     *
     * @throws \Exception
     * @return Form Options
     *
     */
    public function options(): Form
    {
        return \FormSchema\Generator\Form::make()->withField(
            Field::email('email')->setLabel('User Email')
                ->setRequired(true)->setValue('Email of the user')
        )->getSchema();
    }

    /**
     * The user email is the same as the settings.
     *
     * @param string|array $settings ['email' => '' ]
     *
     * @return bool Does the user have the given email?
     */
    public function evaluate($settings): bool
    {
        if(is_string($settings)) {
            $settings = json_decode($settings, true);
        }
        try {
            return $this->user()->data()->email() === $settings['email'];
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Return the filter name.
     *
     * @return string Filter name
     */
    public function name()
    {
        return 'User has Email';
    }

    /**
     * Return the filter description.
     *
     * @return string Filter description
     */
    public function description()
    {
        return 'User has a given email address';
    }

    /**
     * Return the filter alias.
     *
     * @return string Filter alias
     */
    public function alias()
    {
        return 'user_email_is';
    }

    public static function clearOn(): array
    {
        return [
            DataUserUpdated::class => fn(DataUserUpdated $event) => app(UserRepository::class)->getByDataProviderId($event->dataUser->id())->id()
        ];
    }
}
