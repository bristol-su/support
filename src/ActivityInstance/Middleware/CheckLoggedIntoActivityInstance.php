<?php

namespace BristolSU\Support\ActivityInstance\Middleware;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceRepository;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\Support\ActivityInstance\Exceptions\NotInActivityInstanceException;
use BristolSU\Support\Authentication\Contracts\Authentication;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLoggedIntoActivityInstance
{

    /**
     * @var ActivityInstanceResolver
     */
    private $activityInstanceResolver;
    /**
     * @var ActivityInstanceRepository
     */
    private $repository;

    public function __construct(ActivityInstanceResolver $activityInstanceResolver, ActivityInstanceRepository $repository)
    {
        $this->activityInstanceResolver = $activityInstanceResolver;
        $this->repository = $repository;
    }

    public function handle(Request $request, \Closure $next)
    {
        try {
            $this->activityInstanceResolver->getActivityInstance();
        } catch (NotInActivityInstanceException $exception) {
            $activity = $request->route('activity_slug');
            $resourceType = $activity->activity_for;
            $resourceId = $this->resourceId($resourceType);
            try {
                $activityInstance = $this->repository->firstFor($activity->id, $resourceType, $resourceId);
            } catch (ModelNotFoundException $e) {
                $activityInstance = $this->repository->create($activity->id,$resourceType,$resourceId,$activity->name,
                    'Default activity instance for activity ' . $activity->name . ' (#' . $activity->id . ')');
            }
            $this->activityInstanceResolver->setActivityInstance($activityInstance);
        }
        return $next($request);
    }

    private function resourceId($resourceType)
    {
        $authentication = app()->make(Authentication::class);
        if ($resourceType === 'user') {
            $model = $authentication->getUser();
        } elseif ($resourceType === 'group') {
            $model = $authentication->getGroup();
        } elseif ($resourceType === 'role') {
            $model = $authentication->getRole();
        }
        if(!$model) {
            throw new \Exception(sprintf('You must be logged in as a %s', $resourceType), 403);
        }
        return $model->id;
    }

}