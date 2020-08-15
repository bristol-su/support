<?php

namespace BristolSU\Support\Progress\Handlers\Database\Models;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{

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
     * Relationship between progress and an activity instance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activityInstance()
    {
        return $this->belongsTo(ActivityInstance::class);
    }


}
