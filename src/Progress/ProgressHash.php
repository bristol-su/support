<?php

namespace BristolSU\Support\Progress;

use Database\Factories\ProgressHashFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressHash extends Model
{
    use HasFactory;

    protected $table = 'progress_change_hashes';

    protected $primaryKey = 'item_key';

    public $incrementing = false;

    protected $fillable = [
        'item_key',
        'hash'
    ];

    /**
     * Get the Progress Hash Value.
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Get Progress Item Key Value.
     *
     * @return string
     */
    public function getItemKey(): string
    {
        return $this->item_key;
    }

    public function scopeByHash(Builder $query, string $hash = null): void
    {
        $query->where('hash', $hash);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new ProgressHashFactory();
    }
}
