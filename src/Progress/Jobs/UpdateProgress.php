<?php


namespace BristolSU\Support\Progress\Jobs;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Progress\ProgressExport;
use BristolSU\Support\Progress\Snapshot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateProgress implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * @var Activity
     */
    private $activity;
    
    private string $driver;

    public function __construct(Activity $activity, string $driver = 'database')
    {
        $this->activity = $activity;
        $this->driver = $driver;
    }

    public function handle(Snapshot $snapshot)
    {
        $progresses = $snapshot->ofActivity($this->activity);
        
        ProgressExport::driver($this->driver)->saveMany($progresses);
    }
}
