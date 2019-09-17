<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\EventStore\EventStore;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use BristolSU\Support\User\User;
use Faker\Generator as Faker;

$factory->define(EventStore::class, function (Faker $faker) {
    return [
        'module_instance_id' => function() {
            return factory(ModuleInstance::class)->create()->id;
        },
        'event' => 'EventClass',
        'keywords' => ['foo' => 'bar'],
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'group_id' => null,
        'role_id' => null
    ];
});
