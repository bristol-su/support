<?php

namespace BristolSU\Support\Connection;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Revision\HasRevisions;
use Database\Factories\ConnectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a connection to a third party.
 */
class Connection extends Model
{
    use HasRevisions, HasFactory;

    /**
     * The table the data is stored in.
     *
     * @var string
     */
    protected $table = 'connection_instances';

    /**
     * Additional attributes for the model.
     *
     * - Connector: returns the registered connector
     *
     * @var array
     */
    protected $appends = ['connector'];

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'alias', 'settings'
    ];

    /**
     * Hidden attributes. The settings attribute may contain API keys, so should stay hidden from requests.
     *
     * @var array
     */
    protected $hidden = ['settings'];

    /**
     * Cast the settings attribute to an array.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * Automatically set the model user ID based on the current authenticated user.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->user_id === null && app(Authentication::class)->hasUser()) {
                $model->user_id = app(Authentication::class)->getUser()->id();
            }
        });

        static::addGlobalScope(new AccessibleConnectionScope());
    }

    /**
     * Get the connector linked to this connection.
     *
     * @return RegisteredConnector
     */
    public function getConnectorAttribute()
    {
        return $this->connector();
    }

    /**
     * Get the connector linked to this connection.
     *
     * @return RegisteredConnector
     */
    public function connector()
    {
        return app(\BristolSU\Support\Connection\Contracts\ConnectorRepository::class)->get($this->alias);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ConnectionFactory();
    }
}
