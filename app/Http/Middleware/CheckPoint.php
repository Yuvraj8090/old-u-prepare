<?php

namespace App\Http\Middleware;

use App\Models\MIS\Permission;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckPoint
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $proceed = 0;

        if(auth()->user()->hasRole('Admin'))
        {
            $proceed = 1;
        }
        elseif(auth()->user()->permissions()->count())
        {
            foreach(auth()->user()->permissions as $permission)
            {
                if(in_array($permission->name, $permissions))
                {
                    $proceed = 1;
                    break;
                }
            }
        }

        if($proceed)
        {
            return $next($request);
        }

        abort(403, 'Access Denied');
    }
}
