<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PagePermission;

class CheckPageAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $pageKey
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $pageKey)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Owner always has full access
        if ($user->isOwner()) {
            return $next($request);
        }

        // Check if admin has access to this page
        if ($user->isAdmin()) {
            if (!PagePermission::canAdminAccess($pageKey)) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut. Hubungi Owner untuk mengubah hak akses.');
            }
        }

        return $next($request);
    }
}
