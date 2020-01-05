<?php

use BristolSU\Support\Connection\Connection;
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

$factory->define(Connection::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'user_id' => 1,
        'alias' => $faker->unique()->word,
        'settings' => [
            'foo' => 'bar',
            'baz' => 'que'
        ],
    ];
});
