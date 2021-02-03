<?php


namespace BristolSU\Support\Revision;

use BristolSU\Support\Authentication\Contracts\Authentication;
use Venturecraft\Revisionable\RevisionableTrait;

trait HasRevisions
{
    use RevisionableTrait;

    /**
     * Maximum number of revisions to save for any given model.
     *
     * @var int
     */
    protected $historyLimit;

    /**
     * Should revisions be deleted when there is a greater number than the historyLimit?
     *
     * @var bool
     */
    protected $revisionCleanup;

    /**
     * Set the configuration properties required by the base implementation from the config.
     */
    public function initializeHasRevisions()
    {
        $this->historyLimit = config('support.revision.cleanup.limit', 10000);
        $this->revisionCleanup = config('support.revision.cleanup.enabled', true);
    }
    
    /**
     * Attempt to find the ID of the current user.
     *
     * @return int|null
     */
    public function getSystemUserId()
    {
        $user = app(Authentication::class)->getUser();

        return ($user === null ? null : $user->id());
    }
}
