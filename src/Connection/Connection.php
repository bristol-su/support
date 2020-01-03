<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Authentication\Contracts\UserAuthentication;
use BristolSU\Support\Connection\Contracts\Connector;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{

    protected $table = 'connection_instances';

    protected $appends = ['connector'];

    protected $fillable = [
        'name', 'description', 'alias', 'settings'
    ];

    protected $hidden = ['settings'];

    protected $casts = [
        'settings' => 'array'
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if($model->user_id === null) {
                $model->user_id = app(UserAuthentication::class)->getUser()->control_id;
            }
        });
    
    }

    public function scopeAccessible(Builder $builder)
    {
        $builder->where('user_id', app(UserAuthentication::class)->getUser()->control_id);
    }

    /**
     * @return RegisteredConnector
     */
    public function getConnectorAttribute()
    {
        return app(\BristolSU\Support\Connection\Contracts\ConnectorRepository::class)->get($this->alias);
    }

    /**
     * @return RegisteredConnector
     */
    public function connector()
    {
        return $this->connector;
    }
}