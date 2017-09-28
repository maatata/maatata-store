<?php

namespace App\Yantrana\Middleware;

use Closure;
use Session;
use URL;
use Illuminate\Contracts\Auth\Guard;

class AuthenticateMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return __apiResponse([
                    'message' => __('Please login to complete request.'),
                    'auth_info' => getUserAuthInfo(9),
                ], 9);
            }

            Session::put('intendedUrl', URL::current());

            return redirect()->route('user.login')
                             ->with([
                                'error' => true,
                                'message' => __('Please login to complete request.'),
                            ]);
        }

        return $next($request);
    }
}
