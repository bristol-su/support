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
use BristolSU\Support\Filters\Contracts\FilterTester;
use BristolSU\Support\Filters\Events\AudienceChanged;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshFilterResult implements ShouldQueue
{
    use Queueable;

    private array $filters;
    private $event;

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
        // Get all filter instances/models that may have been affected by an event, and fire that as an event
        $events = collect($this->filters)
            ->map(function (Filter $filter) use ($filterInstanceRepository) {
                $callbackResult = $filter::clearOn()[get_class($this->event)]($this->event);
                if ($callbackResult !== false) {
                    $model = match (true) {
                        $filter instanceof UserFilter => app(UserRepository::class)->getById($callbackResult),
                        $filter instanceof GroupFilter => app(GroupRepository::class)->getById($callbackResult),
                        $filter instanceof RoleFilter => app(RoleRepository::class)->getById($callbackResult),
                        default => throw new Exception('Filters must be one of user, group or role00')
                    };
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
