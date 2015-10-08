<?php namespace Modules\User\Http\Middleware; 

use Closure;

class LoggedInMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	return $next($request);
    }
    
}
