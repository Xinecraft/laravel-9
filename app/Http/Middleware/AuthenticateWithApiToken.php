<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class AuthenticateWithApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->api_token;

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = \App\Models\User::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // all good authenticate user
        Auth::login($user);

        return $next($request);
    }
}
