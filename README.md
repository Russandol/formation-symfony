# Formation Symfony

## Initialisation du projet

### Docker

J'utilise Docker et non le CLI de Symfony. Voici les commandes Docker utilisées : 

Installer le container : 
```shell
docker-compose build
```

Lancer le container : 
```shell
docker-compose up
```

Se connecter au container avec l'utilisateur "application" :
```shell
docker-compose exec -u application web bash
```

Note : pour effectuer toutes les autres commandes, il faut être connecté au container.

### Installation de symfony

Créer le projet : 
```shell
composer create-project symfony/skeleton:"7.0.*" skeleton
```
Tout le projet symfony va être installé dans le dossier "skeleton". Je n'ai jamais trouvé
comment installer un projet directement à la racine d'un dossier...

Il faut donc déplacer tous les dossiers / fichiers à la racine. On peut le faire avec la
commande : 
```shell
mv skeleton/* ./
```
Seuls deux fichiers ne sont pas déplacés par cette commande (dont .gitignore). Il ne faut pas
oublier de les déplacer à la mano. Une fois tous les dossiers / fichiers déplacés, on peut
supprimer le dossier "skeleton".

### Installation des bundles utiles

Installer tous les bundles webapp :
```shell
composer require webapp
```
L'installation va détecter que nous sommes sur Docker et va proposer de faire des modifications
en conséquence. J'avais essayé une fois mais ça avait foutu le bordel et je n'arrivais pas à me
connecter à la base de données. Du coup maintenant je mets "no" à cette question.

Installer le bundle pour les fixtures :
```shell
composer require orm-fixtures --dev
```

Installer le bundle qui permet de créer des données fakes :
```shell
composer require fakerphp/faker
```

### Configuration de Symfony

Pour éviter les erreurs de routing (page 404) il faut ajouter un fichier .htaccess
dans le dossier /public.

Dupliquer le fichier .env et le nommer .env.local. Et à la fin du fichier rajouter la ligne :
```php
DATABASE_URL="mysql://root:root@mariadb:3306/%BASE_NAME%?charset=utf8"
```
Il faut bien entendu changer le %BASE_NAME% par le nom de la base définie dans le 
docker-compose.yml

## Les entités

### Création

Créer une entité : 
```shell
bin/console make:entity
```
La commande nous pose plusieurs questions pour ajouter des champs à notre entité.
Si le champ correspond à une relation avec une autre table, il faut préciser "relation" en 
type de champs.

On utilise la même commande pour ajouter des champs à une entité existante. 
Doctrine va détecter au départ si l'entité existe déjà et se débrouiller ensuite.

Cette commande crée les fichiers suivants pour une entité nommée "Posts" : 
```
src
----Entity
--------Posts.php
----Repository
--------PostsRepository.php
```

Une fois l'entité créé on peut créer une migration. Les migrations permettent de 
mettre à jour la base de données avec un historique des modifications.
```shell
bin/console make:migration
```

Enfin on peut jouer les migrations pour mettre à jour la base de données avec la commande :
```shell
bin/console doctrine:migrations:migrate
```

### Rajouter un CRUD

Créer un crud pour l'entité "Posts" :
```shell
bin/console make:crud
```

Cette commande va rajouter et remplir les fichiers suivants :
```
src
----Controller
--------PostsController.php
----Form
--------PostsType.php
Templates
-----Posts
--------_delete_form.html.twig
--------_form.html.twig
--------edit.html.twig
--------index.html.twig
--------new.html.twig
--------show.html.twig
```

Toutes la procédure CRUD est maintenant disponible dans l'application à l'url "/posts".

### Rajouter des fixtures en base

Créer une fixture :
```shell
bin/console make:fixture
```

Cette commande va créer un fichier de fixture : 
```
src
----DataFixtures
--------AppFixtures.php
```

Exemple d'ajout de données en utilisant une fixture :
```php
public function load(ObjectManager $manager): void
{
    $post = new \App\Entity\Posts();
    $post->setTitle('Mon Super Post');
    $post->setContent('Je suis vraiment un BG à faire des posts aussi cool !');

    $manager->persist($post);
    $manager->flush();
}
```

Importer les fixtures en base de données :
```shell
bin/console doctrine:fixtures:load
```

Attention : à chaque fois que cette méthode est lancée, les tables de la base sont vidées.

## Les utilisateurs

Créer un utilisateur : 
```shell
bin/console make:user
```

Cette commande va créer les fichiers suivants : 
```
src
----Entity
--------User.php
----Repository
--------UserRepository.php
```

Créer l'interface de connexion :
```shell
bin/console make:auth
```

Cette commande va créer les fichiers suivants (en fonction des noms définis lors de la procédure):
```
src
----Controller
--------SecurityUserController.php
----Security
--------AppUserAuthenticator.php
Templates
-----Security
--------login.html.twig
```

Le fichier /config/packages/security.yaml

## Utiles

Créer un controller
```shell
bin/console make:controller
```

Vider le cache :
```shell
bin/console cache:clear
```

Pour éviter d'avoir le cache sur Twig en environnment de dev, dans le fichier 
config/packages/twig.yaml, rajouter le code suivant : 
```yaml
when@dev:
    twig:
        cache: false
```