<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);//generate a unique sentence with 4 words for the article title
        return [
            'user_id' => User::inRandomOrder()->value('id'),//assign a random user ID from existing users
            'title' => $title,//set the title attribute
            'slug' => Str::slug($title),//create a URL-friendly slug from the title. slug: howa url
            'excerpt' => fake()->sentence(12),//generate a short excerpt with 12 words. excerpt: mo9tataf.
            'content' => fake()->paragraphs(3, true),//generate 3
        ];
    }
}
