<?php

namespace Database\Factories;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Permissions\Models\ModelPermission;
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

$factory->define(ModelPermission::class, function (Faker $faker) {
    return [
        'ability' => $faker->word,
        'model' => 'user',
        'model_id' => function () {
            return factory(User::class)->create()->id;
        },
        'result' => $faker->boolean,
        'module_instance_id' => null
    ];
});

$factory->state(ModelPermission::class, 'user', function () {
    return [
        'model' => 'user',
        'model_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});

$factory->state(ModelPermission::class, 'group', function () {
    return [
        'model' => 'group',
        'model_id' => 1,
    ];
});

$factory->state(ModelPermission::class, 'role', function () {
    return [
        'model' => 'role',
        'model_id' => 1,
    ];
});

$factory->state(ModelPermission::class, 'logic', function () {
    return [
        'model' => 'logic',
        'model_id' => function () {
            return factory(Logic::class)->create()->id;
        },
    ];
});

$factory->state(ModelPermission::class, 'module', function () {
    return [
        'module_instance_id' => function () {
            return factory(\BristolSU\Support\ModuleInstance\ModuleInstance::class)->create()->id;
        }
    ];
});

$factory->state(ModelPermission::class, 'true', ['result' => true]);
$factory->state(ModelPermission::class, 'false', ['result' => false]);
