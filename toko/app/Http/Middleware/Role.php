<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Role as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Role
{

    public function handle($request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $currentUrl = trim($request->path(), '/'); // Normalize the URL path (remove leading/trailing slashes)

        // // Check if the URL exists in the menus table
        $menu = DB::table('menus')->where('link', $currentUrl)->first();
        if ($menu) {
            // Check if the user's role_id has access to the menu_id
            $hasAccess = DB::table('menu_roles')
                ->where('role_id', $user->role_id)
                ->where('menu_id', $menu->id)
                ->exists();
            //dd($hasAccess);

            if (!$hasAccess) {
                // Restrict access if no matching record in aksesmenu
                abort(403, 'Unauthorized access');
            }
        } else {
            // Optional: You might want to restrict access if the URL is not in the menus table
            // abort(404, 'Menu not found');
        }
        return $next($request);
    }
}
