<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, $designation) {
       if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->designation === $designation) {
            return $next($request);
        }

        // Unauthorized access
        abort(403, 'Unauthorized action.');
    }
    
}