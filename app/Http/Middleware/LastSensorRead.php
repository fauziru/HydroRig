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
            $read = ReadNutrisi::create(['sensor_id' => $id, 'read_nutrisi' => 0]);
            $read->created_at = $request->route('sensor')['last_read'];
            $read->save();
        }
        // dd($request->route('sensor')['id']);
        $expiresAt = Carbon::now()->addSeconds(10);
        Cache::put('sensor-is-connect-' . $id, true, $expiresAt);
        // last read update
        Sensor::where('id', $id)->update(['last_read' => (new \DateTime())->format("Y-m-d H:i:s")]);
        return $next($request);
    }
}
