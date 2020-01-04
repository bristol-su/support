<?php

namespace BristolSU\Support\Logic\Audience;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\Support\Logic\Contracts\Audience\AudienceMemberFactory as AudienceMemberFactoryContract;

/**
 * Class AudienceFactory
 * @package BristolSU\Support\Logic
 */
class AudienceMemberFactory implements AudienceMemberFactoryContract
{

    public function fromUser(User $user): AudienceMember {
        return new AudienceMember($user);
    }

}
