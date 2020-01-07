<?php

namespace BristolSU\Support\Logic\Audience;


use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;
use BristolSU\Support\Logic\Contracts\Audience\LogicAudience as LogicAudienceContract;
use BristolSU\Support\Logic\Logic;

/**
 * Get the audience of a logic group
 * 
 * @see LogicAudienceContract
 */
class LogicAudience extends LogicAudienceContract
{
    /**
     * User Repository, to resolve all users from
     * 
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * Audience member factory to construct audience members
     * 
     * @var AudienceMemberFactoryContract
     */
    private $audienceMemberFactory;

    /**
     * @param UserRepository $userRepository User Repository to resolve all users from
     * @param AudienceMemberFactoryContract $audienceMemberFactory Audience member factory to construct audience members from users
     */
    public function __construct(UserRepository $userRepository, AudienceMemberFactoryContract $audienceMemberFactory)
    {
        $this->userRepository = $userRepository;
        $this->audienceMemberFactory = $audienceMemberFactory;
    }

    /**
     * Get the audience of a logic group
     * 
     * Will return an array of AudienceMember objects representing the audience of the given logic group.
     * 
     * @param Logic $logic Logic group to get the audience for
     * @return AudienceMember[]
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

    /**
     * Get the possible audience
     * 
     * Returns all users of the portal as AudienceMembers. 
     * 
     * @return \Generator Used as an array, eases memory constraints at runtime
     */
    private function possibleAudience()
    {
        foreach ($this->userRepository->all() as $user) {
            yield $this->audienceMemberFactory->fromUser($user);
        }
    }

}
