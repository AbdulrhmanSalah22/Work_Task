<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Verified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if ($user) {
            if ( $user->verified_at == null) {
                return response()->json(['message' => 'You must Verify Your Account First']);
            }
        return $next($request);
        }
        return response()->json(['message' => 'You Should Register First']);
    }
}
