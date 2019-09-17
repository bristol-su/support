<?php

namespace BristolSU\Support\Logic;

use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Logic\Contracts\AudienceFactory as AudienceFactoryContract;
use BristolSU\Support\User\Contracts\UserRepository;

class AudienceFactory implements AudienceFactoryContract
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var GroupRepository
     */
    private $groupRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct(UserRepository $userRepository, GroupRepository $groupRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->roleRepository = $roleRepository;
    }

    public function for($for) {
        if($for === 'user') {
            return $this->userRepository->all();
        } if($for === 'group') {
            return collect($this->groupRepository->all());
        } if($for === 'role') {
            return $this->roleRepository->all();
        }
        return [];
    }

}
