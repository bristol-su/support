<?php

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ActivityInstance\ActivityInstance;
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

$factory->define(ActivityInstance::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'activity_id' => function() {
            return factory(Activity::class)->create()->id;
        },
        'resource_type' => 'user',
        'resource_id' => 1
    ];
});

$factory->state(Activity::class, 'user', ['resource_type' => 'user']);
$factory->state(Activity::class, 'group', ['resource_type' => 'group']);
$factory->state(Activity::class, 'role', ['resource_type' => 'role']);
