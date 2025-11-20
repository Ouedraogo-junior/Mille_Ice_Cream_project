<?php
// app/Http/Middleware/EnsureUserIsSuperAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSuperAdmin
{
  public function handle(Request $request, Closure $next): Response
  {
      if (!auth()->user()?->isSuperAdmin()) {
          abort(403, "Accès refusé. Seuls les super-administrateurs peuvent effectuer cette action.");
      }

      return $next($request);
  }
}