<?php

namespace Database\Factories;

$factory->define(\BristolSU\Support\Action\History\ActionHistory::class, function(\Faker\Generator $faker) {
    return [
        'action_instance_id' => function() {
            return factory(\BristolSU\Support\Action\ActionInstance::class)->create()->id;
        },
        'event_fields' => [],
        'settings' => [],
        'message' => $faker->sentence,
        'success' => $faker->boolean
    ];
});
