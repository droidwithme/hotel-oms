<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleChecker
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
        // get the user role
        $userRole = Auth::user()->role;
        // Get the required roles from the route passed in the web.php
        $rolesAssignedToRoute = $this->getAllowedRoleForRoute($request->route());
        if ($userRole == $rolesAssignedToRoute) {
            return $next($request);
        }
        return abort(401, 'Unauthorized');
    }

    /**
     * Function to get all the roles that are assigned to the route in web.php
     * @param $route
     * @return roles assigned to the route.
     */
    private function getAllowedRoleForRoute($route)
    {
        $actions = $route->getAction();
        return isset($actions['role']) ? $actions['role'] : null;
    }
}
