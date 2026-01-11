<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BotAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->header('Authorization');

        // Remove "Bearer " prefix if present
        if ($token && str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }

        $botToken = Setting::get('bot_super_token', '');

        if (empty($botToken)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bot authentication not configured',
            ], 500);
        }

        if ($token !== $botToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Invalid bot token.',
            ], 401);
        }

        return $next($request);
    }
}
