<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
          Any user with a permission less than 3 is not a super admin and
          receives a 403 un authorized action response.
        */
        if (Auth::user()->permission < 3) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
