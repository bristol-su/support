<?php

namespace BristolSU\Support\Filters\Listeners;

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
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\Events\AudienceChanged;
use BristolSU\Support\Filters\Jobs\RefreshFilterResult;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
     * @param FilterTester $filterTester
     */
    public function __construct(FilterRepository $filterRepository, FilterInstanceRepository $filterInstanceRepository, FilterTester $filterTester)
    {
        //
        $this->filterRepository = $filterRepository;
        $this->filterInstanceRepository = $filterInstanceRepository;
        $this->filterTester = $filterTester;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($event)
    {
        // Get all filter instances/models that may have been affected by an event, and fire that as an event
        collect($this->filterRepository->getAll())
            ->filter(fn(Filter $filter) => array_key_exists(get_class($event), $filter::clearOn()))
            ->each(function (Filter $filter) use ($event) {
                dispatch(new RefreshFilterResult($event, $filter));
            });
    }

}
