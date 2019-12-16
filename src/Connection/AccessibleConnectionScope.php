<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\Contracts\UserAuthentication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AccessibleConnectionScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('user_id', app(UserAuthentication::class)->getUser()->control_id);
        // TODO Build in adding open or shared with ones
    }
}