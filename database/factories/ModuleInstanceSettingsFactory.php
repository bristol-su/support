<?php

use Faker\Generator as Faker;

$factory->define(\BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting::class, function (Faker $faker) {
    return [
        'key' => $faker->word,
        'value' => $faker->word,
        'module_instance_id' => function () {
            return factory(\BristolSU\Support\ModuleInstance\ModuleInstance::class)->create()->id;
        },
        'encoded' => false
    ];
});
