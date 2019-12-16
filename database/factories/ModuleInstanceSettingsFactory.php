<?php

use Faker\Generator as Faker;

$factory->define(\BristolSU\Support\ModuleInstance\Settings\ModuleInstanceSetting::class, function (Faker $faker) {
    return [
        'settings' => [
            'foo' => $faker->word,
            'bar' => $faker->word,
            'baz' => $faker->word,
        ]
    ];
});
