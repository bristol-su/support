<?php

use BristolSU\Support\Progress\Handlers\Database\Models\ModuleInstanceProgress;
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

$factory->define(ModuleInstanceProgress::class, function (Faker $faker) {
    return [
        'module_instance_id' => function () {
            return factory(\BristolSU\Support\ModuleInstance\ModuleInstance::class)->create()->id;
        },
        'progress_id' => function () {
            return factory(Progress::class)->create()->id;
        },
        'mandatory' => $faker->boolean,
        'complete' => $faker->boolean,
        'percentage' => $faker->numberBetween(0, 100),
        'active' => $faker->boolean,
        'visible' => $faker->boolean
    ];
});
