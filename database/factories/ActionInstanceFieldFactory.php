<?php

use BristolSU\Support\Action\ActionInstance;
use BristolSU\Support\Action\ActionInstanceField;
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

$factory->define(ActionInstanceField::class, function (Faker $faker) {
    return [
        'event_field' => $faker->word,
        'action_field' => $faker->text,
        'action_instance_id' => function() {
            return factory(ActionInstance::class)->create()->id;
        },
    ];
});
