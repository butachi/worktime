<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use App\Contracts\Authentication;

class GuestMiddleware
{
    private $auth;

    public function __construct(Authentication $auth)
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
        if ($this->auth->check()) {
            return Redirect::route('home');
        }

        return $next($request);
    }
}
