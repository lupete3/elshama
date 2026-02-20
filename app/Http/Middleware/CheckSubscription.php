<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 🔹 Bypass si c'est un Super Admin
        if ($user->hasRoleString('Super Admin')) {
            return $next($request);
        }

        $tenant = $user->tenant;

        if (!$tenant || !$tenant->is_active) {
            return abort(403, 'Votre organisation est suspendue. Contactez l\'administration.');
        }

        $subscription = $tenant->activeSubscription()->first();

        if (!$subscription || !$subscription->isValid()) {
            return abort(403, 'Votre abonnement a expiré. Merci de renouveler.');
        }

        return $next($request);
    }
}
