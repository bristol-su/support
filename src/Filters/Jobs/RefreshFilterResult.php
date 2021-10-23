<?php

namespace BristolSU\Support\Filters\Jobs;

use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\Support\Action\Actions\Log;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\Events\AudienceChanged;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * A job that takes an event and filters, and fires a AudienceChanged for each
 * filter instance that needs to be changed
 */
class RefreshFilterResult implements ShouldQueue
{
    use Queueable, Dispatchable;

    private array $filters;
    private $event;

    /**
     * @param object $event The event that was fired
     * @param array $filters The filters that are affected
     */
    public function __construct($event, array $filters)
    {
        $this->filters = $filters;
        $this->event = $event;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(FilterInstanceRepository $filterInstanceRepository)
    {
        $events = collect($this->filters) // Iterate through all the filters that are affected
            ->map(function (Filter $filter) use ($filterInstanceRepository) {
                // Get the control model affected by the event.
                $callbackResult = $filter::clearOn()[get_class($this->event)]($this->event);
                if ($callbackResult !== false) {
                    $model = match (true) {
                        $filter instanceof UserFilter => app(UserRepository::class)->getById($callbackResult),
                        $filter instanceof GroupFilter => app(GroupRepository::class)->getById($callbackResult),
                        $filter instanceof RoleFilter => app(RoleRepository::class)->getById($callbackResult),
                        default => throw new Exception('Filters must be one of user, group or role00')
                    };
                    // Return an AudienceChanged event for each filter instance affected
                    return $filterInstanceRepository->all()
                        ->filter(fn(FilterInstance $filterInstance) => $filterInstance->alias() === $filter->alias())
                        ->map(fn(FilterInstance $filterInstance) => new AudienceChanged($filterInstance, $model));
                }
                return [];
            })
            ->flatten(1);

        foreach ($events as $refreshEvent) {
            event($refreshEvent);
        }
    }

}
