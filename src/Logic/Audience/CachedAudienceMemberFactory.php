<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;
use BristolSU\Support\Logic\Logic;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;

/**
 * Creates an audience member
 */
class CachedAudienceMemberFactory implements AudienceMemberFactoryContract
{

    /**
     * @var AudienceMemberFactoryContract
     */
    private $audienceMemberFactory;
    /**
     * @var Repository
     */
    private $cache;

    public function __construct(AudienceMemberFactoryContract $audienceMemberFactory, Repository $cache)
    {
        $this->audienceMemberFactory = $audienceMemberFactory;
        $this->cache = $cache;
    }

    /**
     * Audience members who have access to a given resource in some way.
     *
     * @param User|Group|Role $resource Resource audience members must have access to
     * @return Collection
     */
    public function withAccessToResource($resource)
    {
        return $this->cache->remember(
            static::class . '@withAccessToResource:' . get_class($resource) . ':' . $resource->id(),
            86400,
            function() use ($resource) {
                return $this->audienceMemberFactory->withAccessToResource($resource);
            }
        );
    }

    /**
     * Audience members who have access to a logic group, with a certain resource.
     *
     * This function will return all audience members who have an audience in the logic group which uses a given resource.
     * @param User|Group|Role $resource Resource that must be in the logic group
     * @param Logic $logic Logic group the resource must be in for an audience member
     *
     * @return Collection
     */
    public function withAccessToLogicGroupWithResource($resource, Logic $logic)
    {
        return $this->cache->remember(
            static::class . '@withAccessToLogicGroupWithResource:' . get_class($resource) . ':' . $resource->id() . ':' . $logic->id,
            86400,
            function() use ($resource, $logic) {
                return $this->audienceMemberFactory->withAccessToLogicGroupWithResource($resource, $logic);
            }
        );
    }

    /**
     * Create an audience member from a user and filter it down to the given logic
     *
     * @param User $user User to create the audience member from
     *
     * @param Logic $logic
     * @return AudienceMember
     */
    public function fromUserInLogic(User $user, Logic $logic)
    {
        return $this->cache->remember(
            static::class . '@fromUserInLogic' . ':' . $user->id() . ':' . $logic->id,
            86400,
            function() use ($user, $logic) {
                return $this->audienceMemberFactory->fromUserInLogic($user, $logic);
            }
        );
    }

    /**
     * Create an audience member from a user
     *
     * @param User $user User to create the audience member from
     *
     * @return AudienceMember
     */
    public function fromUser(User $user)
    {
        return $this->audienceMemberFactory->fromUser($user);
    }
}
