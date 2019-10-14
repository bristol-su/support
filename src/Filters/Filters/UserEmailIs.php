<?php


namespace BristolSU\Support\Filters\Filters;


use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\User\Contracts\UserRepository;
use Illuminate\Support\Arr;

class UserEmailIs extends UserFilter
{


    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get possible options as an array
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
     * Test if the filter passes
     *
     * @param Object $model Group, Role or User
     * @param string $settings Key of the chosen option
     *
     * @return bool
     */
    public function evaluate($settings): bool
    {
        return $this->model()->email === $settings['email'];
    }

    public function name()
    {
        return 'User has Email';
    }

    public function description()
    {
        return 'User has a given email address';
    }

    public function alias()
    {
        return 'user_email_is';
    }

    public function audience($settings)
    {
        return $this->userRepository->getWhereEmail($settings['email']);
    }
}
