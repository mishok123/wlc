<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\SmfAuthService;

class SmfAuthMiddleware
{
    protected $smfAuth;

    public function __construct(SmfAuthService $smfAuth)
    {
        $this->smfAuth = $smfAuth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $smfUser = $this->smfAuth->authenticateFromSmf();
        $current = Auth::user();

        // If SMF says "guest" but Laravel has an SMF-linked user, log them out (true SSO behavior).
        if ($smfUser === null) {
            if ($current !== null && !empty($current->smf_id)) {
                Auth::logout();
            }

            return $next($request);
        }

        // If SMF says "logged in", ensure Laravel matches that user.
        if ($current === null || (int) ($current->id ?? 0) !== (int) $smfUser->id) {
            Auth::login($smfUser);
        }

        return $next($request);
    }
}
