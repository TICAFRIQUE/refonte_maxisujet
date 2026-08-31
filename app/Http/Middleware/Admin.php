<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Autorise uniquement les comptes ayant un rôle d'administration réel (Spatie),
        // jamais sur la seule base de la colonne texte "role" (falsifiable côté inscription).
        if (Auth::check() && Auth::user()->hasAnyRole(['administrateur', 'developpeur', 'superadmin'])) {
            return $next($request);
        }

        // Si l'utilisateur est un client ou n'est pas authentifié
        return redirect()->route('admin.login')->withError('Session expirée ou accès non autorisé, veuillez vous connecter');
    }
}
