<?php

namespace App\Http\Middleware;

use Closure;
use Validator;
use App\Models\Setting;

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
        $settings = Setting::first();
        $validator = Validator::make($request->all(), [
            'secret' => 'required',
        ]);

        if ($validator->fails()) return response()->json(['Secret Key is required'], 400);

        if ($request->secret != $settings->api_key) return response()->json(['Secret Key is not valid'], 404);

        return $next($request);
    }
}
