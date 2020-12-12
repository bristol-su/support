<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Scope for connections accessible to the current user
 */
class AccessibleConnectionScope implements Scope
{

    /**
     * Only return connections which are accessible by the current user
     *
     * @param Builder $builder Query builder
     * @param Model $model Model being scoped
     */
    public function apply(Builder $builder, Model $model)
    {
        $auth = app(Authentication::class);
        if($auth->hasUser()) {
            $builder->where('user_id', $auth->getUser()->id());
        }
    }
}
