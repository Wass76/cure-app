<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use Illuminate\Auth\AuthenticationException;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
    protected function unauthenticated($request, array $guards)
{
    if ($request->expectsJson()) {
        response()->json(['message' => 'Unauthorized. Token is missing or invalid.'], 401)->send();
        exit; // Stop further processing
    }

    // Default behavior for non-API requests (you can remove this if not needed):
    throw new AuthenticationException(
        'Unauthenticated.', $guards, $this->redirectTo($request)
    );
}

}
