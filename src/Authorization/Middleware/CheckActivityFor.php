<?php

namespace BristolSU\Support\Authorization\Middleware;

use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authorization\Exception\ActivityRequiresParticipant;
use BristolSU\Support\Logic\Facade\LogicTester;
use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to check the user is in the for logic group
 */
class CheckActivityFor
{
    /**
     * Holds the authentication contract
     * 
     * @var Authentication
     */
    private $authentication;

    /**
     * Initialise the middleware
     * 
     * @param Authentication $authentication Authentication contract to resolve the user information
     */
    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check if the user is in the logic group forLogic
     * 
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ActivityRequiresParticipant
     */
    public function handle(Request $request, Closure $next)
    {
        $activity = $request->route('activity_slug');
        $logic = $activity->forLogic;
        if (!LogicTester::evaluate($logic, $this->authentication->getUser(), $this->authentication->getGroup(), $this->authentication->getRole())) {
            throw ActivityRequiresParticipant::createWithActivity($activity, 'You do not have access to this activity,', 403);
        }
        return $next($request);
    }
}