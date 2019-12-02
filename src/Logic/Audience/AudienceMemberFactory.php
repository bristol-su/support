<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\Support\Control\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;

/**
 * Class AudienceFactory
 * @package BristolSU\Support\Logic
 */
class AudienceMemberFactory implements AudienceMemberFactoryContract
{

    public function fromUser(User $user) {
        return new AudienceMember($user);
    }

}