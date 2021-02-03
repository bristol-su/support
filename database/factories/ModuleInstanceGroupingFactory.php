<?php

use Faker\Generator as Faker;

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

$factory->define(\BristolSU\Support\ModuleInstance\ModuleInstanceGrouping::class, function (Faker $faker) {
    return [
        'heading' => join(' ', $faker->words()),
    ];
});
