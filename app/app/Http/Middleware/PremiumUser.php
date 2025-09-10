<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PremiumUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        // 未ログイン or プレミアでない場合は403
        if (!$user || !$user->is_premium) {
            abort(403, 'この機能はプレミア会員限定です。');
        }

        return $next($request);
    }
}
