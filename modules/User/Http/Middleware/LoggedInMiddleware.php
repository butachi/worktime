<?php namespace modules\User\Http\Middleware;

use Closure;
use Modules\Core\Contracts\Authentication;

class LoggedInMiddleware
{
    private $auth;
    
    public function __construct(Authentication $auth) {
        $this->authen = $auth;
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
        if (! $this->auth->check()) {
            return redirect()->guest('auth/login');
        }
        return $next($request);
    }
}
