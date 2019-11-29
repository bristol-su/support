<?php

use BristolSU\Support\Completion\CompletionConditionInstance;
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

$factory->define(CompletionConditionInstance::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'alias' => $faker->word,
        'settings' => [
            'foo' => $faker->word,
            'bar' => $faker->word,
            'baz' => $faker->word,
        ]
    ];
});
