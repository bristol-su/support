<?php

namespace Database\Factories;

use BristolSU\Support\Permissions\Models\ModuleInstancePermission;
use Faker\Generator as Faker;

$factory->define(ModuleInstancePermission::class, function (Faker $faker) {
    return [
        'ability' => '',
        'logic_id' => function () {
            return factory(\BristolSU\Support\Logic\Logic::class)->create()->id;
        },
        'module_instance_id' => function () {
            return factory(\BristolSU\Support\ModuleInstance\ModuleInstance::class)->create()->id;
        }
    ];
});
