<?php
// 📁 app/Http/Middleware/CheckRole.php
// Middleware personnalisé pour vérifier les rôles

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Vérifier si l'utilisateur est actif
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été désactivé.');
        }

        // Vérifier le rôle
        if ($user->role !== $role) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}