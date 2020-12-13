<?php

namespace Database\Factories;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Settings\Saved\SavedSettingModel;
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

$factory->define(SavedSettingModel::class, function (Faker $faker) {
    return [
        'key' => $faker->unique()->word,
        'value' => $faker->word,
        'user_id' => function () {
            return factory(User::class)->create()->id();
        }
    ];
});
