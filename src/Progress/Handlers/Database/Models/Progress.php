<?php

namespace BristolSU\Support\Progress\Handlers\Database\Models;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use Database\Factories\ProgressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    protected $fillable = [
        'activity_instance_id',
        'complete',
        'percentage',
        'timestamp'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function moduleInstanceProgress()
    {
        return $this->hasMany(ModuleInstanceProgress::class);
    }

    /**
     * Relationship between progress and an activity instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activityInstance()
    {
        return $this->belongsTo(ActivityInstance::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ProgressFactory();
    }
}
