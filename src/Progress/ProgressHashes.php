<?php

namespace BristolSU\Support\Progress;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProgressHashes extends Model
{
    protected $table = 'progress_change_hashes';

    protected $primaryKey = 'item_key';

    public $incrementing = false;

    protected $fillable = [
        'item_key',
        'hash'
    ];

    /**
     * Primary key for Model.
     *
     * @var string
     */
    private $item_key;

    /**
     * Hash of the saved progress.
     *
     * @var string
     */
    private $hash;

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
}
