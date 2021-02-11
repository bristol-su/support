<?php

namespace Database\Factories;

use BristolSU\Support\ActivityInstance\ActivityInstance;
use BristolSU\Support\Progress\Handlers\Database\Models\Progress;
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

$factory->define(Progress::class, function (Faker $faker) {
    return [
        'activity_instance_id' => function () {
            return factory(ActivityInstance::class)->create()->id;
        },
        'complete' => $faker->boolean,
        'percentage' => $faker->numberBetween(0, 100),
        'timestamp' => $faker->dateTime
    ];
});
