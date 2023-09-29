<?php

namespace App\Http\Middleware;

use Closure;
use Otserver\Visitor;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Visitor::isLogged()) {
            return redirect('accountmanagement/login');
        }
        return $next($request);
    }
}
