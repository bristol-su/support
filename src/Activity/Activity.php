<?php

namespace BristolSU\Support\Activity;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\ModuleInstanceGrouping;
use BristolSU\Support\Revision\HasRevisions;
use BristolSU\Support\Eloquent\CascadeRestoreDeletes;
use Carbon\Carbon;
use Database\Factories\ActivityFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Activity Model.
 */
class Activity extends Model
{
    use HasRevisions, HasFactory, SoftDeletes, CascadeSoftDeletes, CascadeRestoreDeletes;

    protected $cascadeDeletes = ['activityInstances', 'moduleInstances', 'moduleGrouping'];

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'activity_for',
        'for_logic',
        'admin_logic',
        'start_date',
        'end_date',
        'slug',
        'type',
        'enabled',
        'user_id',
        'image_url'
    ];

    /**
     * Attributes to be casted.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'enabled' => 'boolean'
    ];

    /**
     * Initialise an Activity model.
     *
     * Set up creating event to set the slug automatically
     * Save the User ID of the current user on creation
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::creating(function ($model) {
            if ($model->slug === null) {
                $model->slug = Str::slug($model->name);
            }
            if ($model->user_id === null && app(Authentication::class)->hasUser()) {
                $model->user_id = app(Authentication::class)->getUser()->id();
            }
        });
    }

    /**
     * Module Instance relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleInstances()
    {
        return $this->hasMany(ModuleInstance::class);
    }

    public function moduleGrouping()
    {
        return $this->hasMany(ModuleInstanceGrouping::class);
    }

    /**
     * Scope only enabled activities.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnabled(Builder $query)
    {
        return $query->where('enabled', true);
    }

    /**
     * For logic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forLogic()
    {
        return $this->belongsTo(Logic::class, 'for_logic');
    }

    /**
     * Admin logic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminLogic()
    {
        return $this->belongsTo(Logic::class, 'admin_logic');
    }

    /**
     * Active scope.
     *
     * Only returns activities which are either not time sensitive, or within the correct time frame
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query
            ->where(['start_date' => null, 'end_date'=>null])
            ->orWhere([
                ['start_date', '<=', Carbon::now()],
                ['end_date', '>=', Carbon::now()]
            ]);
    }

    /**
     * Is the activity completable?
     *
     * Can the activity be completed? An open activity cannot, but a completable activity can be
     *
     * @return bool
     */
    public function isCompletable(): bool
    {
        return $this->type === 'completable' || $this->type === 'multi-completable';
    }

    /**
     * Activity Instance relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityInstances()
    {
        return $this->hasMany(ActivityInstance::class);
    }

    /**
     * Get the user who created the activity.
     *
     * @throws \Exception If the user ID is null
     * @return \BristolSU\ControlDB\Contracts\Models\User
     */
    public function user(): \BristolSU\ControlDB\Contracts\Models\User
    {
        if ($this->user_id === null) {
            throw new \Exception(sprintf('Activity #%u is not owned by a user.', $this->id));
        }

        return app(User::class)->getById($this->user_id);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ActivityFactory();
    }
}
