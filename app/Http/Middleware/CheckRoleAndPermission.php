<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class CheckRoleAndPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next, $check, ...$role)
    {
        $user           = auth()->user();
        $user_role_name = $user->role->name;

	// New code Feb 2025
        foreach($role as $perm)
        {
            if($user->hasPermission($perm))
            {
                return $next($request);
            }
        }


        // dd(auth()->user()->role->name,$check);

        if (!$user || !$user->role)
        {
            abort(403, 'Access Denied');
        }
        elseif (
            $check == "ONE" && 
            ($role[0] == $user_role_name || $user->role_id == 1)
        )
        {
            return $next($request);
        }
        elseif (
            $check == "THREE" && 
            $role[0] == $user_role_name
        )
        {
            return $next($request);
        }
        elseif (
            $check == "TWO" && 
            in_array($user_role_name, [
                'PROCUREMENT-LEVEL-TWO', 'PMU-LEVEL-ONE', 'PMU-LEVEL-TWO', 'PMU-LEVEL-TWO', 'PIU-LEVEL-TWO-PWD',
                'PIU-LEVEL-TWO-RWD', 'PIU-LEVEL-TWO-FOREST', 'PIU-LEVEL-TWO-USDMA','ENVIRONMENT-LEVEL-TWO',
                'SOCIAL-LEVEL-TWO', 'SUPER-ADMIN'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "FOUR" && 
            in_array($user_role_name, [
                'PROCUREMENT-LEVEL-TWO',
                'PROCUREMENT-LEVEL-THREE'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "FIVE" && 
            in_array($user_role_name, [
                'PIU-LEVEL-TWO-PWD',
                'PIU-LEVEL-TWO-RWD', 
                'PMU-LEVEL-TWO',
                'PIU-LEVEL-TWO-FOREST', 
                'PIU-LEVEL-TWO-USDMA'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "SIX" && 
            in_array($user_role_name, [ // New Code 7 Feb 2024
                'PMU-LEVEL-THREE',
                'PIU-LEVEL-THREE-PWD',
                'PIU-LEVEL-THREE-RWD',
                'PIU-LEVEL-THREE-FOREST',
                'PIU-LEVEL-THREE-USDMA'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "SEVEN" && 
            in_array($user_role_name, [ // New Code 7 Feb 2024
                'PMU-PROCUREMENT-LEVEL-THREE',
                'PWD-PROCUREMENT-LEVEL-THREE',
                'RWD-PROCUREMENT-LEVEL-THREE',
                'FOREST-PROCUREMENT-LEVEL-THREE',
                'USDMA-PROCUREMENT-LEVEL-THREE'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "EIGHT" && 
            in_array($user_role_name, [ // New Code 14 Feb 2024
                'ENVIRONMENT-LEVEL-TWO',
                'SOCIAL-LEVEL-TWO'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "NINE" && 
            (auth()->user()->role->level ==  "THREE") && 
            in_array($user_role_name, [ // New Code 14 Feb 2024
                'PWD-ENVIRONMENT-LEVEL-THREE',
                'PMU-ENVIRONMENT-LEVEL-THREE',
                'RWD-ENVIRONMENT-LEVEL-THREE',
                'FROEST-ENVIRONMENT-LEVEL-THREE',
                'USDMA-ENVIRONMENT-LEVEL-THREE',
                'PWD-SOCIAL-LEVEL-THREE',
                'PMU-SOCIAL-LEVEL-THREE',
                'RWD-SOCIAL-LEVEL-THREE',
                'FROEST-SOCIAL-LEVEL-THREE',
                'USDMA-SOCIAL-LEVEL-THREE'
            ])
        )
        {
            return $next($request);
        }
        elseif (
            $check == "TEN" &&
            in_array($user_role_name, [ 
                'PROCUREMENT-LEVEL-TWO', 'PMU-LEVEL-ONE', 'PMU-LEVEL-TWO', 'PMU-LEVEL-TWO', 'PIU-LEVEL-TWO-PWD',
                'PIU-LEVEL-TWO-RWD', 'PIU-LEVEL-TWO-FOREST', 'PIU-LEVEL-TWO-USDMA','ENVIRONMENT-LEVEL-TWO',
                'SOCIAL-LEVEL-TWO',
            
                'PWD-ENVIRONMENT-LEVEL-THREE',
                'PMU-ENVIRONMENT-LEVEL-THREE',
                'RWD-ENVIRONMENT-LEVEL-THREE',
                'FROEST-ENVIRONMENT-LEVEL-THREE',
                'USDMA-ENVIRONMENT-LEVEL-THREE',
            
                'PWD-SOCIAL-LEVEL-THREE',
                'PMU-SOCIAL-LEVEL-THREE',
                'RWD-SOCIAL-LEVEL-THREE',
                'FROEST-SOCIAL-LEVEL-THREE',
                'USDMA-SOCIAL-LEVEL-THREE',
            
                'PWD-ENVIRONMENT',
                'PMU-ENVIRONMENT',
                'USDMA-ENVIRONMENT',
                'RWD-ENVIRONMENT',
                'FROEST-ENVIRONMENT',
                'PWD-SOCIAL',
                'PMU-SOCIAL',
                'FOREST-SOCIAL',
                'RWD-SOCIAL',
                'USDMA-SOCIAL',
                'PMU-LEVEL-TWO',
                'PIU-LEVEL-TWO-PWD',
                'PIU-LEVEL-TWO-RWD',
                'PIU-LEVEL-TWO-FOREST',
                'PIU-LEVEL-TWO-USDMA',
                'ENVIRONMENT-LEVEL-TWO',
                'SOCIAL-LEVEL-TWO',
                'PMU-LEVEL-ONE',
                'SUPER-ADMIN'
            ])
        )
        {
            return $next($request);
        }
        elseif(
            $check == "ELEVEN" &&
            auth()->user()->role->level ==  "FOUR" && 
            in_array($user_role_name, [ // New Code 14 Feb 2024
                'PWD-ENVIRONMENT-FOUR',
                'PMU-ENVIRONMENT-FOUR',
                'RWD-ENVIRONMENT-FOUR',
                'FROEST-ENVIRONMENT-FOUR',
                'USDMA-ENVIRONMENT-FOUR',
                'PWD-SOCIAL-FOUR',
                'PMU-SOCIAL-FOUR',
                'RWD-SOCIAL-FOUR',
                'FROEST-SOCIAL-FOUR',
                'USDMA-SOCIAL-FOUR'
            ])
        )
        {
            return $next($request);
        }
        elseif(
            $check == 'grievance' &&
            in_array(auth()->user()->role->department, ['GRIEVANCE'])
        )
        {
            return $next($request);
        }

        abort(404, 'Access Denied');
    }
}
