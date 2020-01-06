<?php


namespace BristolSU\Support\Filters\Filters\User;


use BristolSU\Support\DataPlatform\Contracts\Repositories\User as DataUserRepository;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use \BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;

/**
 * Does the user have the given email?
 */
class UserEmailIs extends UserFilter
{

    /**
     * Holds the data user repository
     * 
     * @var DataUserRepository
     */
    private $dataUserRepository;

    /**
     * @param DataUserRepository $dataUserRepository To resolve the user email
     */
    public function __construct(DataUserRepository $dataUserRepository)
    {
        $this->dataUserRepository = $dataUserRepository;
    }

    /**
     * Get possible options as an array
     * 
     * Options are [ 'email' => 'emailaddress@example.com' ]
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'email' => ''
        ];
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
            $user = $this->dataUserRepository->getById($this->user()->dataPlatformId());
        } catch (\Exception $e) {
            return false;
        }        
        return $user->email === $settings['email'];
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
