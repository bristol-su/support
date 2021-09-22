<?php

namespace BristolSU\Support\Authentication\Middleware;

use BristolSU\Support\Authentication\Exception\PasswordUnconfirmed;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class HasConfirmedPassword
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @throws PasswordUnconfirmed
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->shouldConfirmPassword($request)) {
            throw new PasswordUnconfirmed();
        }

        return $next($request);
    }

    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldConfirmPassword(Request $request): bool
    {
        // How many seconds have passed since the password was last confirmed.
        $confirmedAt = Carbon::now()->unix() - $request->session()->get('portal-auth.password_confirmed_at', 0);

        return $confirmedAt > 1800;
    }
}
