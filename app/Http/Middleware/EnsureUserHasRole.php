<?php

namespace App\Http\Middleware;

use Closure;
use HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @param string $role
     * @return RedirectResponse
     * @throws HttpResponseException
     */
    public function handle(Request $request, Closure $next, string $role): RedirectResponse
    {
        if ($request->user()->roles()->where('nom', $role)->exists())
            return $next($request);

        throw new HttpResponseException(response()->json(json_encode([
            'message' => "The user {$request->user()->name} can't access to this endpoint"]),
            RESPONSE::HTTP_FORBIDDEN
        ));
    }
}
