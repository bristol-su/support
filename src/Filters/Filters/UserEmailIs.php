<?php


namespace BristolSU\Support\Filters\Filters;


use BristolSU\Support\DataPlatform\Contracts\Repositories\User as DataUserRepository;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use \BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;

/**
 * Class UserEmailIs
 * @package BristolSU\Support\Filters\Filters
 */
class UserEmailIs extends UserFilter
{


    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var DataUserRepository
     */
    private $dataUserRepository;

    /**
     * UserEmailIs constructor.
     * @param UserRepository $userRepository
     * @param DataUserRepository $dataUserRepository
     */
    public function __construct(UserRepository $userRepository, DataUserRepository $dataUserRepository)
    {
        $this->userRepository = $userRepository;
        $this->dataUserRepository = $dataUserRepository;
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
     * @param string $settings Key of the chosen option
     *
     * @return bool
     */
    public function evaluate($settings): bool
    {
        $user = $this->dataUserRepository->getById($this->model()->uc_uid);
        return $user->email === $settings['email'];
    }

    /**
     * @return mixed|string
     */
    public function name()
    {
        return 'User has Email';
    }

    /**
     * @return mixed|string
     */
    public function description()
    {
        return 'User has a given email address';
    }

    /**
     * @return mixed|string
     */
    public function alias()
    {
        return 'user_email_is';
    }

    /**
     * @param $settings
     * @return \Illuminate\Support\Collection|mixed
     */
    public function audience($settings)
    {
        $user = $this->dataUserRepository->getByEmail($settings['email']);
        return collect([$this->userRepository->findByDataId($user->id())]);
    }
}
