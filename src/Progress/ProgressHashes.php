<?php

namespace BristolSU\Support\Progress;

use Illuminate\Database\Eloquent\Model;

class ProgressHashes extends model {

    protected $table = 'progress_change_hashes';

    protected $primaryKey = 'item_key';

    public $incrementing = false;

    protected $fillable = [
        'item_key',
        'hash'
    ];

}