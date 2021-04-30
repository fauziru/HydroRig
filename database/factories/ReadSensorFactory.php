<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ReadNutrisi;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(ReadNutrisi::class, function (Faker $faker) {
    return [
        'sensor_id' => 1,
        'read_nutrisi' => rand(1,1000)
    ];
});
