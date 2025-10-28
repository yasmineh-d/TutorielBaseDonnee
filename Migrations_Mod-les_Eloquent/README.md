# 📘 2.1.4 — Requêtes CRUD avec Eloquent
 
 ℹ️ Prérequis : la base de données doit déjà être remplie avec les seeders du chapitre 2.1.3. Vous allez maintenant apprendre à manipuler les données directement à travers Eloquent, sans passer par d’interface graphique.
 
 ## 📒 Glossaire minute
 - **CRUD** : opérations de base sur une ressource (Create, Read, Update, Delete).
 - **Eloquent** : ORM (Object Relational Mapper) de Laravel permettant d’interagir avec la base de données via des objets PHP.
 - **Tinker** : console interactive pour exécuter des commandes Laravel.
 - **Scope** : filtre réutilisable sur un modèle.
 - **Query chaining** : enchaînement fluide de méthodes (`where()->orderBy()->get()`).
 
 ## 🎯 Objectif pédagogique
 Savoir manipuler la base de données du Blog Laravel à partir de Tinker en utilisant les méthodes Eloquent :
 1. Créer (`create`, `save`)
 2. Lire (`all`, `find`, `where`, `with`)
 3. Mettre à jour (`update`, `save`)
 4. Supprimer (`delete`)
 5. Gérer les relations et les requêtes chaînées
 
 ## 🧠 Définition théorique
 Eloquent agit comme une couche intermédiaire entre PHP et MySQL : chaque modèle représente une table, et chaque instance correspond à une ligne de cette table.
 
 SQL vs Eloquent, exemples d’équivalence :
 
 ```sql
 SELECT * FROM articles;
 ```
 
 ```php
 Article::all();
 ```
 
 ```sql
 -- WHERE user_id = 1
 ```
 
 ```php
 Article::where('user_id', 1)->get();
 ```
 
 ```sql
 INSERT INTO articles (...)
 ```
 
 ```php
 Article::create([...]);
 ```
 
 ```sql
 UPDATE articles ...
 ```
 
 ```php
 $article->update([...]);
 ```
 
 ```sql
 DELETE FROM articles ...
 ```
 
 ```php
 $article->delete();
 ```
 
 ## 🛠 Tutoriel pratique
 
 ### Étape 1 : Lancer Tinker
 Tinker permet d’exécuter du code Laravel sans passer par un fichier PHP.
 
 ```bash
 php artisan tinker
 ```
 
 ### Étape 2 : Créer des données (Create)
 On peut insérer une nouvelle donnée avec `create()` ou en instanciant un objet puis en appelant `save()`.
 
 #### 🧩 Méthode 1 — Avec `create()`
 
 ```php
 use App\Models\Article;
 
 $article = Article::create([
     'user_id' => 1,
     'title' => 'Premier article manuel',
     'slug' => 'premier-article',
     'excerpt' => 'Introduction à Eloquent CRUD',
     'content' => 'Ceci est un test d’ajout via Tinker.'
 ]);
 ```
 
 💡 Les champs utilisés doivent être déclarés dans `$fillable` du modèle.
 
 #### 🧩 Méthode 2 — Avec `new` et `save()`
 
 ```php
 $a = new Article;
 $a->user_id = 1;
 $a->title = 'Deuxième article';
 $a->slug = 'deuxieme-article';
 $a->save();
 ```
 
 ✅ Vérification :
 
 ```php
 Article::count();
 ```
 
 ### Étape 3 : Lire les données (Read)
 On peut lire toutes les données, filtrer ou charger des relations.
 
 #### 📋 Lister tous les articles
 ```php
 Article::all();
 ```
 
 #### 🔍 Trouver un article précis
 ```php
 Article::find(1);
 ```
 
 #### 🎯 Filtrer par mot-clé
 ```php
 Article::where('title', 'like', '%article%')->get();
 ```
 
 #### 🔗 Lire avec les relations (user, tags)
 ```php
 Article::with(['user','tags'])->first();
 ```
 
 #### 📊 Compter les articles par utilisateur
 ```php
 \App\Models\User::withCount('articles')->get();
 ```
 
 ### Étape 4 : Modifier des données (Update)
 Eloquent permet de modifier directement un ou plusieurs enregistrements.
 
 #### 🧩 Exemple simple
 ```php
 $article = Article::find(1);
 $article->update(['title' => 'Titre modifié']);
 ```
 
 #### Variante
 ```php
 $article->title = 'Nouveau titre modifié';
 $article->save();
 ```
 
 ✅ Vérifier la mise à jour :
 ```php
 Article::find(1)->title;
 ```
 
 ### Étape 5 : Supprimer des données (Delete)
 Une simple méthode `delete()` supprime l’enregistrement courant.
 
 ```php
 $article = Article::find(1);
 $article->delete();
 ```
 
 💡 Vérification :
 ```php
 Article::find(1); // null
 ```
 
 ### Étape 6 : Manipuler les relations
 
 #### 🧩 Ajouter un tag à un article
 ```php
 $a = Article::first();
 $a->tags()->attach(1);
 ```
 
 #### 🧩 Retirer un tag
 ```php
 $a->tags()->detach(1);
 ```
 
 #### 🧩 Remplacer tous les tags
 ```php
 $a->tags()->sync([2,3,4]);
 ```
 
 ### Étape 7 : Filtrer et trier (Query Builder)
 Eloquent permet d’enchaîner plusieurs conditions facilement.
 
 #### 🔽 Trier par date
 ```php
 Article::orderBy('created_at','desc')->take(5)->get();
 ```
 
 #### 🎯 Sélectionner certains champs
 ```php
 Article::select('id','title','slug')->get();
 ```
 
 #### 🔗 Combiner plusieurs conditions
 ```php
 Article::where('user_id',1)->orderBy('title')->limit(3)->get();
 ```
 
 ### Étape 8 : Créer un scope personnalisé (optionnel)
 Un scope permet de créer une requête réutilisable dans le modèle.
 
 Dans `app/Models/Article.php` :
 
 ```php
 public function scopeRecent($query)
 {
     return $query->orderBy('created_at', 'desc')->take(5);
 }
 ```
 
 Dans Tinker :
 
 ```php
 Article::recent()->get();
 ```
 
 ## 📘 Bonus — Combiner Eloquent et Query Builder
 Vous pouvez exécuter des requêtes avancées directement sur Eloquent.
 
 ```php
 Article::where('title','like','%laravel%')
     ->selectRaw('user_id, count(*) as total')
     ->groupBy('user_id')
     ->get();
 ```
 
 ## 🧾 Résumé et points-clés
 - `create()` / `save()` → création
 - `all()` / `find()` / `where()` → lecture
 - `update()` / `save()` → mise à jour
 - `delete()` → suppression
 - `with()` / `attach()` / `sync()` → relations
 - `scopeNom()` → scope personnalisé (ex: `Article::recent()->get()`)
 
 ---
 
 Référence du chapitre original :
 https://solicode-web-mobile.github.io/autoformation-mobile/schema-eloquent/crud-eloquent/