<?php

use BristolSU\Support\Activity\Activity;
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

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'activity_for' => 'user',
        'for_logic' => function () {
            return factory(Logic::class)->create()->id;
        },
        'admin_logic' => function () {
            return factory(Logic::class)->create()->id;
        },
        'type' => 'open',
        'start_date' => $faker->dateTimeInInterval('-1 year', '-5 days'),
        'end_date' => $faker->dateTimeInInterval('+5 days', '+1 year'),
        'enabled' => true,
        'user_id' => function () {
            return factory(\BristolSU\ControlDB\Models\User::class)->create()->id();
        }
    ];
});

$factory->state(Activity::class, 'user', ['activity_for' => 'user']);
$factory->state(Activity::class, 'group', ['activity_for' => 'group']);
$factory->state(Activity::class, 'always_active', ['start_date' => null, 'end_date' => null]);
$factory->state(Activity::class, 'inactive', function (Faker $faker) {
    return [
        'start_date' => $faker->dateTimeInInterval('-30 days', '-3 days'),
        'end_date' => $faker->dateTimeInInterval('-3 days', '-1 day')
    ];
});
