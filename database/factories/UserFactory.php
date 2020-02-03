<?php

use BristolSU\Support\User\User;
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

$factory->define(User::class, function(Faker $faker) {
    return [
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('secret'),
        'control_id' => function() {
            return factory(\BristolSU\ControlDB\Models\User::class)->create()->id();
        },
        'auth_provider' => null,
        'auth_provider_id' => null
    ];
});
