<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\Support\Control\Contracts\Repositories\Group as GroupRepository;
use BristolSU\Support\Control\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Control\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Control\Models\User as UserModel;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Logic\Audience\Specification\InFilterAudienceSpecification;
use BristolSU\Support\Logic\Audience\Specification\NotInFilterAudienceSpecification;
use BristolSU\Support\Logic\Contracts\Audience\AudienceFactory as AudienceFactoryContract;
use BristolSU\Support\Logic\Contracts\Audience\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Contracts\LogicTester;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Logic\Specification\AndSpecification;
use BristolSU\Support\Logic\Specification\OrSpecification;
use Illuminate\Support\Facades\Log;

/**
 * Class LogicAudience
 * @package BristolSU\Support\Logic
 */
class LogicAudience implements LogicAudienceContract
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

    /**
     * LogicAudience constructor.
     * @param UserRepository $userRepository
     * @param GroupRepository $groupRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(UserRepository $userRepository,
                                GroupRepository $groupRepository,
                                RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param Logic $logic
     * @return mixed
     */
    public function audience(Logic $logic)
    {
        $audienceMembers = [];
        foreach ($this->possibleAudience() as $audienceMember) {
            $audienceMember->filterForLogic($logic);
            if($audienceMember->hasAudience()) {
                $audienceMembers[] = $audienceMember;
            }
        }
        return $audienceMembers;
    }

    public function possibleAudience()
    {
        foreach ($this->userRepository->all() as $user) {
            yield new AudienceMember($user);
        }
    }

}
