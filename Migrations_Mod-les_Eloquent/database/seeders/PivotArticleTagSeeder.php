<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Tag;


class PivotArticleTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void //assign tags to articles
    {
        $tagIds = Tag::pluck('id'); // katjib kol les IDs dyal les tags men la table 'tags'

        Article::all()->each(function ($article) use ($tagIds) { //iterate over each article
            $article->tags()->sync($tagIds->random(rand(1, 4))->all());//attach 1 to 3 random tags to each article.  
        });
    }
}       
