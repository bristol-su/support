<?php

namespace Database\Factories;

use BristolSU\ControlDB\Models\User;
use BristolSU\Support\Settings\Saved\SavedSettingModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SavedSettingModelFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SavedSettingModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->word,
            'user_id' => fn() => User::factory()->create()->id(),
            'visibility' => 'global'
        ];
    }

}
