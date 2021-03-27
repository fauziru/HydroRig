<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Cache;
use App\Models\Sensor;
use App\Models\ReadNutrisi;

class LastSensorRead
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
        $id = $request->route('sensor')['id'];
        if (!Cache::has('sensor-is-connect-' . $id)) {
            ReadNutrisi::create(['sensor_id' => $id, 'read_nutrisi' => null]);
        }
        // dd($request->route('sensor')['id']);
        $expiresAt = Carbon::now()->addSeconds(10);
        Cache::put('sensor-is-connect-' . $id, true, $expiresAt);
        // last read update
        Sensor::where('id', $id)->update(['last_read' => (new \DateTime())->format("Y-m-d H:i:s")]);
        return $next($request);
    }
}
