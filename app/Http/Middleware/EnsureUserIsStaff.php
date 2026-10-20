<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStaff
{
    /**
     * Only active staff accounts may reach the administration area.
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403, 'Votre compte n\'est pas autorisé à accéder à l\'administration.');
        }

        if (! $user->isStaff()) {
            abort(403, 'Accès réservé à l\'équipe.');
        }

        if ($role === 'admin' && ! $user->isAdmin()) {
            abort(403, 'Cette section est réservée aux administrateurs.');
        }

        return $next($request);
    }
}
