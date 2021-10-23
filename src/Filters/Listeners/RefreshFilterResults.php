<?php

namespace BristolSU\Support\Filters\Listeners;

use BristolSU\Support\Filters\Contracts\FilterInstanceRepository;
use BristolSU\Support\Filters\Contracts\FilterRepository;
use BristolSU\Support\Filters\Contracts\Filters\Filter;
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\Jobs\RefreshFilterResult;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * A listener that binds to any events listened to by filters, and starts the process of clearing the logic cache
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
     * @param FilterTester $filterTester
     */
    public function __construct(FilterRepository $filterRepository, FilterInstanceRepository $filterInstanceRepository, FilterTester $filterTester)
    {
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
        // Get all filters that listen to this event, and fire a RefreshFilterResult job with the event and the filter affected
        collect($this->filterRepository->getAll())
            ->filter(fn(Filter $filter) => array_key_exists(get_class($event), $filter::clearOn()))
            ->each(fn (Filter $filter) => dispatch(new RefreshFilterResult($event, [$filter])));
    }

}
