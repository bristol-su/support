<?php

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\Logic\Logic;
use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting;
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

$factory->define(ModuleInstance::class, function (Faker $faker) {
    return [
        'alias' => $faker->word,
        'activity_id' => function () {
            return factory(Activity::class)->create()->id;
        },
        'name' => $faker->word,
        'description' => $faker->text,
        'active' => function () {
            return factory(Logic::class)->create()->id;
        },
        'visible' => function () {
            return factory(Logic::class)->create()->id;
        },
        'mandatory' => function () {
            return factory(Logic::class)->create()->id;
        },
        'module_instance_settings_id' => function() {
            return factory(ModuleInstanceSetting::class)->create()->id;
        },
        'module_instance_permissions_id' => function() {
            return factory(ModuleInstancePermission::class)->create()->id;
        },
        'completion_condition_instance_id' => null
    ];
});
