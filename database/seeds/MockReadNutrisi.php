<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MockReadNutrisi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\ReadNutrisi::class, 3000)->create(['created_at' => Carbon::now()]);
        factory(App\Models\ReadNutrisi::class, 3000)->create(['created_at' => Carbon::now()->subDays(7)]);
        factory(App\Models\ReadNutrisi::class, 3000)->create(['created_at' => Carbon::now()->subDays(30)]);
        factory(App\Models\ReadNutrisi::class, 3000)->create(['created_at' => Carbon::now()->subDays(45)]);
    }
}
