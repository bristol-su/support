<?php

use BristolSU\Support\Progress\ProgressHash;
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

$factory->define(ProgressHash::class, function (Faker $faker) {
    return [
        'item_key' => sprintf('%s_%u', $faker->word, $faker->numberBetween(0, 100)),
        'hash' => $faker->sha1
    ];
});
