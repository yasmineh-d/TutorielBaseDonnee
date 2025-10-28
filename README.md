# Migrations & Modèles Eloquent - Laravel 11

## 📋 Vue d'ensemble

Ce guide explique comment créer un schéma relationnel complet pour une application Blog Laravel en utilisant les migrations et les modèles Eloquent.

## 🎯 Objectifs

- Définir les tables et leurs relations (PK/FK)
- Appliquer les contraintes d'intégrité
- Générer les modèles Eloquent correspondants
- Vérifier la cohérence avec Tinker

## 📚 Concepts clés

| Terme | Définition |
|-------|-----------|
| **PK** | Clé primaire - identifiant unique d'une ligne |
| **FK** | Clé étrangère - référence vers une PK d'une autre table |
| **Migration** | Fichier versionné décrivant la structure d'une table |
| **Modèle Eloquent** | Classe PHP représentant une table |
| **Contrainte** | Règle d'intégrité garantissant la cohérence des données |

## 🛠️ Mise en place

### 1. Créer les migrations

```bash
php artisan make:migration create_articles_table
php artisan make:migration create_tags_table
php artisan make:migration create_article_tag_table
```

### 2. Définir la table `articles`

```php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title', 180);
    $table->string('slug', 200)->unique();
    $table->text('excerpt')->nullable();
    $table->longText('content')->nullable();
    $table->timestamps();
});
```

### 3. Définir la table `tags`

```php
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->timestamps();
});
```

### 4. Définir la table pivot `article_tag`

```php
Schema::create('article_tag', function (Blueprint $table) {
    $table->foreignId('article_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->primary(['article_id', 'tag_id']);
});
```

### 5. Exécuter les migrations

```bash
php artisan migrate
```

## 🎨 Créer les modèles

### Générer les modèles

```bash
php artisan make:model Article
php artisan make:model Tag
```

### Exemple de modèle `Article`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'content'
    ];
}
```

### Exemple de modèle `Tag`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];
}
```

## ✅ Vérification avec Tinker

```bash
php artisan tinker
```

```php
>>> App\Models\Article::count();
>>> App\Models\Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
>>> App\Models\Tag::all();
```

## 📊 Types de colonnes courants

| Type | Exemple | Usage |
|------|---------|-------|
| `string` | `$table->string('title', 255)` | Texte court |
| `text` | `$table->text('content')` | Texte long |
| `integer` | `$table->integer('age')` | Nombre entier |
| `boolean` | `$table->boolean('is_active')` | Vrai/Faux |
| `timestamp` | `$table->timestamp('published_at')` | Date et heure |
| `foreignId` | `$table->foreignId('user_id')` | Clé étrangère |
| `json` | `$table->json('meta')` | Données JSON |

## 🔑 Points essentiels

- Les migrations permettent de versionner la structure de la base de données
- Chaque modèle Eloquent représente une table
- `$fillable` définit les champs modifiables en masse
- Les contraintes `cascadeOnDelete()` suppriment automatiquement les enregistrements liés
- Tinker permet de tester les modèles interactivement

## 📖 Prérequis

- Projet Laravel 11 fonctionnel
- Base MySQL configurée dans `.env`

## 🔗 Prochaine étape

Déclaration des relations Eloquent (User → Article, Article ↔ Tag)