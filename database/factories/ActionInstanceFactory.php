<?php

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Logic\Logic;
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

$factory->define(ActionInstance::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'event' => $faker->word,
        'action' => $faker->word,
        'module_instance_id' => function() {
            return factory(ModuleInstance::class)->create()->id;
        },
    ];
});
