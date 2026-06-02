<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id') 
            ?? $request->input('user_id') 
            ?? $request->query('user_id') 
            ?? $request->input('sender_id');

        if (!$userId) {
            $routeUser = $request->route('user') ?? $request->route('id');
            if (is_object($routeUser)) {
                $userId = $routeUser->id;
            } elseif (is_numeric($routeUser)) {
                $userId = $routeUser;
            }
        }

        if ($userId && is_numeric($userId)) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                if (!$user->last_seen || $user->last_seen->lt(now()->subSeconds(10))) {
                    $user->last_seen = now();
                    $user->save();
                }
            }
        }

        return $next($request);
    }
}
