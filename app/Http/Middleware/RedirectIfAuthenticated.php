<?php
// 📁 app/Http/Middleware/RedirectIfAuthenticated.php
// Modifier pour rediriger selon le rôle

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Rediriger selon le rôle
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->role === 'caissier') {
                    return redirect()->route('caisse');
                }
                
                return redirect('/dashboard');
            }
        }

        return $next($request);
    }
}