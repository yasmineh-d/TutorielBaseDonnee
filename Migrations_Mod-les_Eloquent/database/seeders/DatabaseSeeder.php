<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([ //call seeders created
            UserSeeder::class,
            ArticleSeeder::class,
            TagSeeder::class,
            PivotArticleTagSeeder::class,
        ]);
    }
}
