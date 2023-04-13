<?php

namespace App\Http\Middleware;

use Closure;
use HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return RedirectResponse
     *
     * @throws HttpResponseException
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->user()->roles()->where('nom', $role)->exists()) {
            return $next($request);
        }
        throw new HttpResponseException(response()->
        json(json_encode(['message' => "The user {$request->user()->name} can't access to this endpoint"]),
            ResponseAlias::HTTP_FORBIDDEN
        ));
    }
}
