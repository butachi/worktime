<?php namespace modules\User\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Modules\Core\Contracts\Authentication;

class GuestMiddleware
{
    private $auth;
    
    public function __construct(Authentication $auth) {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        if ($this->auth->check())
        {
            return Redirect::route('homepage');
        }
        return $next($request);
    }
}
