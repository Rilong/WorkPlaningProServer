<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Laravel\Passport\Token;

class TokenExpires
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            $user = auth()->user();
            if ($user) {
                $token = $user->token();

                if ($token->expires_at < Carbon::now()) {
                    $token->delete();
                    return response()->json('Token is expired', 401);
                }
            }
        }

        return $next($request);
    }
}
