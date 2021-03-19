<?php

namespace App\Http\Middleware;

use Closure;
use Validator;

class SecretKey
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
        $validator = Validator::make($request->all(), [
            'secret' => 'required',
        ]);

        if ($validator->fails()) return response()->json(['Secret Key is required']);

        if ($request->secret != 'tes') return response()->json(['Secret Key is not valid']);

        return $next($request);
    }
}
