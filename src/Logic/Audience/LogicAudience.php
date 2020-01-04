<?php

namespace BristolSU\Support\Logic\Audience;


use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;
use BristolSU\Support\Logic\Contracts\Audience\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Logic;

/**
 * Class LogicAudience
 * @package BristolSU\Support\Logic
 */
class LogicAudience extends LogicAudienceContract
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var AudienceMemberFactoryContract
     */
    private $audienceMemberFactory;

    /**
     * LogicAudience constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, AudienceMemberFactoryContract $audienceMemberFactory)
    {
        $this->userRepository = $userRepository;
        $this->audienceMemberFactory = $audienceMemberFactory;
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

    private function possibleAudience()
    {
        foreach ($this->userRepository->all() as $user) {
            yield $this->audienceMemberFactory->fromUser($user);
        }
    }

}
