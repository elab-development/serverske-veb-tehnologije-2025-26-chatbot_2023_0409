<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user())
        {
            return response()->json([
                'message' => 'Niste prijavljeni.'], 401);
        }

        if ($role === 'user' && !in_array($request->user()->role, ['user', 'admin']))
        {
            return response()->json([
                'message' => 'Nemate dozvolu za ovu akciju.'], 403);
        }

        if ($role === 'admin' && $request->user()->role !== 'admin')
        {
            return response()->json([
                'message' => 'Nemate dozvolu za ovu akciju.'], 403);
        }
        
        return $next($request);
    }
}
