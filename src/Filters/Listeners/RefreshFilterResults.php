<?php

namespace BristolSU\Support\Filters\Listeners;

use BristolSU\ControlDB\Contracts\Repositories\Group as GroupRepository;
use BristolSU\ControlDB\Contracts\Repositories\Role as RoleRepository;
use BristolSU\ControlDB\Contracts\Repositories\User as UserRepository;
use BristolSU\Support\Filters\Contracts\FilterInstance;
use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Contracts\Filters\GroupFilter;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\Jobs\RefreshFilterResult;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * A listener that binds to any events listened to by filters, and starts the process of clearing the logic cache.
 */
class RefreshFilterResults implements ShouldQueue
{
    use Queueable, Dispatchable;

    private FilterRepository $filterRepository;

    private FilterInstanceRepository $filterInstanceRepository;

    private FilterTester $filterTester;

    /**
     * Create the event listener.
     *
     * @param FilterRepository $filterRepository
     * @param FilterInstanceRepository $filterInstanceRepository
     */
    public function __construct(FilterRepository $filterRepository, FilterInstanceRepository $filterInstanceRepository)
    {
        $this->filterRepository = $filterRepository;
        $this->filterInstanceRepository = $filterInstanceRepository;
        $this->onQueue('logic');
    }

    /**
     * Handle the event.
     *
     * @param mixed $event
     */
    public function handle($event)
    {
        // Get all filters that listen to this event, and fire a RefreshFilterResult job with the event and the filter affected
        $audienceChangedEvents = collect($this->filterRepository->getAll())
            ->filter(fn (Filter $filter) => array_key_exists(get_class($event), $filter::clearOn()))
            ->map(function (Filter $filter) use ($event) {
                // Get the control model affected by the event.

                $callbackResult = $filter::clearOn()[get_class($event)]($event);
                if ($callbackResult !== false) {
                    $model = match (true) {
                        $filter instanceof UserFilter => app(UserRepository::class)->getById($callbackResult),
                        $filter instanceof GroupFilter => app(GroupRepository::class)->getById($callbackResult),
                        $filter instanceof RoleFilter => app(RoleRepository::class)->getById($callbackResult),
                        default => throw new Exception('Filters must be one of user, group or role')
                    };
                    // Return an AudienceChanged event for each filter instance affected
                    return new AudienceChanged(
                        $this->filterInstanceRepository->all()
                            ->filter(fn (FilterInstance $filterInstance) => $filterInstance->alias() === $filter->alias())
                            ->all(),
                        $model
                    );
                }

                return null;
            })
            ->filter();

        foreach ($audienceChangedEvents as $audienceChangedEvent) {
            event($audienceChangedEvent);
        }
    }
}
