# Laravel Framework

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Eloquent Relationships

Laravel Eloquent makes it easy to define and work with relationships between models. Common relationship types are: **One-to-One**, **One-to-Many**, and **Many-to-Many**.

### Quick glossary
- **hasMany / belongsTo** — one-to-many (e.g. `User` → many `Article`).
- **belongsToMany** — many-to-many with a pivot table (e.g. `Article` ↔ `Tag`).
- **Eager loading** — pre-load relations to avoid N+1 queries (`with()` / `withCount()`).

### Practical examples (Blog models)

#### 1) `User` → `Article` (one-to-many)
```php
public function articles()
{
    return $this->hasMany(Article::class);
}
```

#### 2) `Article` → `User` (inverse)
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

#### 3) `Article` ↔ `Tag` (many-to-many via pivot `article_tag`)
```php
public function tags()
{
    return $this->belongsToMany(Tag::class);
}
```

### Eager loading & counts
```php
$articles = App\Models\Article::with(['user','tags'])->get();
$articles = App\Models\Article::withCount('tags')->get();
```

### Test in Tinker
```bash
php artisan tinker
>>> $u = App\Models\User::first();
>>> $u->articles;
>>> $a = App\Models\Article::first();
>>> $a->user;
>>> $a->tags;
```

### Bonus — nested eager loading example
```php
$user = App\Models\User::with('articles.tags')->first();
foreach ($user->articles as $article) {
    echo $article->title . ': ' . $article->tags->pluck('name')->join(', ');
}
```

---

## Seeders & Factories

### ℹ️ Prerequisites
The `User`, `Article` and `Tag` models must already be functional with their relationships properly configured.

### 📒 Quick Glossary
- **Factory** → Template describing the structure of fake data to generate.
- **Seeder** → Automated script for inserting data into the database.
- **Faker** → Built-in generator for random texts, emails, dates and realistic content.
- **firstOrCreate()** → Creates data only if it doesn't exist yet (idempotent).
- **sync()** → Associates multiple records in a many-to-many relationship (`Article ↔ Tag`).

### 🎯 Learning Objective
Learn to automate the creation of realistic and consistent data for a Laravel Blog project, respecting relationships between models:
- Generate users (`UserFactory`)
- Generate tags (`TagFactory`)
- Generate articles linked to a user (`ArticleFactory`)
- Automatically associate articles ↔ tags via `sync()`

### 🧠 Theoretical Definition
Laravel provides a combined Factory + Seeder system to facilitate database population during development:
- Factories define the typical structure of data to generate (titles, content, dates, etc.).
- Seeders orchestrate the insertion of this data in the correct order and desired quantity.
- The system allows reloading a complete database at any time with a simple command:

```bash
php artisan migrate:fresh --seed
```

💡 Each Eloquent model has a dedicated factory in `database/factories/`.

---

### 🛠 Practical Tutorial

#### Step 1: Create the Factories

Factories define the structure of automatically generated data for each model. Create them with the `make:factory` command linked to an existing model.

```bash
php artisan make:factory UserFactory --model=User
php artisan make:factory TagFactory --model=Tag
php artisan make:factory ArticleFactory --model=Article
```

**database/factories/UserFactory.php**

Generates fake users with unique emails.

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
```

**database/factories/TagFactory.php**

Generates unique tags with a name and automatic slug.

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
```

**database/factories/ArticleFactory.php**

Creates articles linked to an existing user (`user_id`), with random title, excerpt and content.

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? 1,
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(12),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
```

#### Step 2: Create the Seeders

Seeders orchestrate data generation in a precise order.

```bash
php artisan make:seeder UserSeeder
php artisan make:seeder TagSeeder
php artisan make:seeder ArticleSeeder
php artisan make:seeder PivotArticleTagSeeder
```

**database/seeders/UserSeeder.php**

Creates a small set of test users.

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(5)->create();
    }
}
```

**database/seeders/TagSeeder.php**

Generates about ten blog tags.

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        Tag::factory()->count(10)->create();
    }
}
```

**database/seeders/ArticleSeeder.php**

Produces 20 articles, each linked to an existing user.

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::factory()->count(20)->create();
    }
}
```

**database/seeders/PivotArticleTagSeeder.php**

Associates 1 to 4 random tags to each article via the `belongsToMany` relationship.

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Tag;

class PivotArticleTagSeeder extends Seeder
{
    public function run(): void
    {
        $tagIds = Tag::pluck('id');
        
        Article::all()->each(function ($article) use ($tagIds) {
            $article->tags()->sync($tagIds->random(rand(1, 4))->all());
        });
    }
}
```

#### Step 3: Orchestration with DatabaseSeeder

Group all seeders in the correct execution order to populate the database at once.

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            PivotArticleTagSeeder::class,
        ]);
    }
}
```

#### Step 4: Execute the Seeders

Empty the database and automatically restart all population:

```bash
php artisan migrate:fresh --seed
```

💡 If everything works, the console will display:
```
Seeding: UserSeeder
Seeding: TagSeeder
Seeding: ArticleSeeder
Seeding: PivotArticleTagSeeder
```

#### Step 5: Verification in Tinker

Verify that the data is properly generated and linked together.

```bash
php artisan tinker
```

```php
>>> App\Models\User::count(); // 5 users
>>> App\Models\Article::count(); // 20 articles
>>> App\Models\Tag::count(); // 10 tags
>>> App\Models\Article::first()->tags->pluck('name'); // associated tags
>>> App\Models\User::first()->articles->count(); // articles of a user
```

✅ If all these commands return results, the database is populated and relationships are functional.

### 🧾 Summary and Key Points

| Element | Role | Example |
|---------|------|---------|
| Factory | Defines the structure of fake data | `TagFactory` |
| Seeder | Executes data generation in the correct order | `TagSeeder` |
| DatabaseSeeder | Coordinates all seeders | `$this->call([...])` |
| Faker | Generates realistic data | `fake()->sentence()` |
| sync() | Links multiple entities in a pivot table | `$article->tags()->sync([...])` |

---

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).