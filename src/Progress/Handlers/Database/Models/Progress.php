<?php

namespace BristolSU\Support\Progress\Handlers\Database\Models;

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

}