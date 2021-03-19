<?php

use Illuminate\Database\Seeder;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sensor')->insert([
            'name_sensor' => 'TDS EC',
            'min_nutrisi' => 900
        ]);
    }
}
