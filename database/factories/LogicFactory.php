<?php

namespace Database\Factories;

use BristolSU\Support\Logic\Logic;
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

$factory->define(Logic::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'user_id' => function () {
            return factory(\BristolSU\ControlDB\Models\User::class)->create()->id();
        }
    ];
});
