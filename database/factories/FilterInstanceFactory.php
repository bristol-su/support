 <?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BristolSU\Support\Filters\FilterInstance;
use Faker\Generator as Faker;

$factory->define(FilterInstance::class, function (Faker $faker) {
    return [
        'alias' => $faker->word,
        'name' => $faker->word,
        'settings' => $faker->randomElements(),
        'logic_id' => function () {
            return factory(\BristolSU\Support\Logic\Logic::class)->create()->id;
        },
        'logic_type' => 'all_true'
    ];
});
