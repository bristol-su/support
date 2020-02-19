<?php


namespace BristolSU\Support\Filters\Filters\User;


use BristolSU\ControlDB\Contracts\Repositories\DataUser as DataUserRepository;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;

/**
 * Does the user have the given email?
 */
class UserEmailIs extends UserFilter
{

    /**
     * Get possible options as an array
     * 
     * Options are [ 'email' => 'emailaddress@example.com' ]
     *
     * @return array
     */
    public function options(): Form
    {
        return \FormSchema\Generator\Form::make()->withField(
            Field::input('email')->inputType('email')->label('User Email')
                ->required(true)->placeholder('Email of the user')
        )->getSchema();
    }

    /**
     * The user email is the same as the settings
     *
     * @param string $settings ['email' => '' ]
     *
     * @return bool Does the user have the given email?
     */
    public function evaluate($settings): bool
    {
        try {
            return $this->user()->data()->email() === $settings['email'];
        } catch (\Exception $e) {
            return false;
        }        
    }

    /**
     * Return the filter name
     * 
     * @return string Filter name
     */
    public function name()
    {
        return 'User has Email';
    }

    /**
     * Return the filter description
     * 
     * @return string Filter description
     */
    public function description()
    {
        return 'User has a given email address';
    }

    /**
     * Return the filter alias
     * 
     * @return string Filter alias
     */
    public function alias()
    {
        return 'user_email_is';
    }
}
