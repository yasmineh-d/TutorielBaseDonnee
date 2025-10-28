<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed> .//return type array of string keys and mixed (chaine, nombre, booleen,etc) values
     */
    public function definition(): array //definition method to specify default values for model attributes
    {
        $name = fake()->unique()->word();//generate a unique word for the tag name
        return [
            'name' => ucfirst($name), //ucfirst to capitalize first letter
            'slug' => Str::slug($name), //Str::slug to create a URL-friendly version of the name . kathayd epace o katkrj3o"-".
        ];
    }
}
