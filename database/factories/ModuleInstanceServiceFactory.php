<?php

namespace Database\Factories;

use BristolSU\Support\Connection\Connection;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceService;
use BristolSU\Support\ModuleInstance\ModuleInstance;
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

$factory->define(ModuleInstanceService::class, function (Faker $faker) {
    return [
        'service' => $faker->word,
        'connection_id' => function () {
            return factory(Connection::class)->create()->id;
        },
        'module_instance_id' => function () {
            return factory(ModuleInstance::class)->create()->id;
        }
    ];
});
