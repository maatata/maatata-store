<?php

namespace App\Yantrana\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Yantrana\Components\ShoppingCart\Repositories\ManageOrderRepository;

class AdminMiddleware
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var ManageOrderRepository - ManageOrder Repository
     */
    protected $manageOrderRepository;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth, ManageOrderRepository $manageOrderRepository)
    {
        $this->auth = $auth;
        $this->manageOrderRepository = $manageOrderRepository;
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
        // if user guest
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return __apiResponse([
                        'message' => __('Please login to complete request.'),
                        'auth_info' => getUserAuthInfo(9),
                    ], 9);
            }

            return redirect()->route('user.login')
                             ->with([
                                'error' => true,
                                'message' => __('Please login to complete request.'),
                            ]);
        }

        // if user not admin
        if (isAdmin() !== true) {
            if ($request->ajax()) {
                return __apiResponse([
                        'message' => __('Unauthorized.'),
                        'auth_info' => getUserAuthInfo(11),
                    ], 11);
            }

            return redirect()->route('home.page')
                             ->with([
                                'error' => true,
                                'message' => __('Unauthorized.'),
                            ]);
        }

         // set the new order placed count
        setInSessionNewOrderPlacedCount($this->manageOrderRepository->fetchNewOrderPlacedCount());

        return $next($request);
    }
}
