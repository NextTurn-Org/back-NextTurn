# back-NextTurn

Backend du projet **NextTurn** : application Symfony exécutée dans Docker, avec une base PostgreSQL et un outil de consultation des logs.

## Ce qui est installé

| Élément | Détail |
|---|---|
| **PHP + Apache** | PHP 8.4, extensions `intl`, `pdo_pgsql`, `zip`, `opcache`, Composer |
| **Symfony** | 7.4 (LTS), dans le dossier `app/` |
| **ORM Pack** | Doctrine ORM, DBAL et Migrations (accès à la base de données) |
| **MakerBundle** | Commandes `make:*` pour générer entités, contrôleurs, etc. (dev uniquement) |
| **PostgreSQL** | Version 17 |
| **Dozzle** | Interface web pour consulter les logs des conteneurs |

## Services Docker

| Service | Rôle | URL / Port |
|---|---|---|
| `php` | Apache + PHP, sert Symfony | http://localhost:8080 |
| `db` | PostgreSQL 17 | `localhost:5432` |
| `logging` | Dozzle (logs en temps réel) | http://localhost:9999 |

Identifiants PostgreSQL (**développement local uniquement**) :

- Base : `app`
- Utilisateur : `app`
- Mot de passe : `secret`

La variable `DATABASE_URL` est définie directement dans `docker-compose.yml` : aucun fichier `.env.local` à créer.

## Démarrage

Prérequis : Docker avec Docker Compose (sous Windows : Docker Desktop avec l'intégration **WSL2**) et Git.

```bash
git clone <url-du-repo> back-NextTurn
cd back-NextTurn
docker compose up -d --build
```

C'est tout. Au premier lancement, le conteneur `php` installe automatiquement les dépendances Composer (cela prend 1 à 2 minutes ; suivre l'avancement avec `docker compose logs -f php`).

Une fois les logs terminés (ligne `Apache ... resuming normal operations`) :

- Symfony : http://localhost:8080
- Dozzle : http://localhost:9999

> Sous Windows, cloner et travailler dans le système de fichiers Linux de WSL2 (par exemple `~/back-NextTurn`), pas dans `/mnt/c/...` : c'est beaucoup plus rapide.

## Structure du repo

```
back-NextTurn/
├── docker/php/
│   ├── Dockerfile        # image PHP 8.4 + Apache + Composer
│   ├── entrypoint.sh     # composer install automatique + droits d'Apache
│   └── vhost.conf        # Apache pointe sur app/public
├── Documentation/
│   └── Docker_README.md  
├── app/                  # projet Symfony
├── docker-compose.yml    # définition des 3 services
├── .gitattributes        # force les fins de ligne LF pour les scripts .sh
└── README.md
```

## Utilisation au quotidien

### Commandes Docker

```bash
docker compose up -d            # démarrer
docker compose ps               # état des services
docker compose logs -f php      # logs d'un service
docker compose stop             # arrêter (conteneurs conservés)
docker compose down             # arrêter et supprimer les conteneurs (base conservée)
docker compose down -v          # idem + supprime le volume : la base est effacée
docker compose up -d --build    # reconstruire après modification du Dockerfile ou de l'entrypoint
```

### Alias recommandés (facultatif)

Pour éviter de retaper les commandes longues, ajouter à `~/.bashrc` (puis `source ~/.bashrc`) :

```bash
alias dcomposer='docker compose exec -u $(id -u):$(id -g) -e COMPOSER_HOME=/tmp/composer php composer'
alias dconsole='docker compose exec -u $(id -u):$(id -g) php php bin/console'
```

Ils s'utilisent depuis la racine du repo. L'option `-u $(id -u):$(id -g)` garantit que les fichiers créés t'appartiennent (et pas à `root`).

### Symfony

```bash
dconsole list                   # toutes les commandes
dcomposer require <paquet>      # ajouter une dépendance
dconsole cache:clear            # vider le cache
```

### Doctrine (base de données)

```bash
dconsole make:entity                         # créer ou modifier une entité
dconsole make:migration                      # générer une migration depuis les entités
dconsole doctrine:migrations:migrate         # appliquer les migrations
dconsole doctrine:migrations:status          # état des migrations
dconsole dbal:run-sql "SELECT 1"             # tester la connexion à la base
```

### MakerBundle

```bash
dconsole list make              # liste des générateurs disponibles
dconsole make:controller        # exemple : créer un contrôleur
```

### Accéder à PostgreSQL

Via le terminal :

```bash
docker compose exec db psql -U app -d app
```

Ou depuis un client SQL (VS Code, DBeaver, etc.) : host `localhost`, port `5432`, utilisateur `app`, mot de passe `secret`, base `app`.

### Consulter les logs

Ouvrir http://localhost:9999 (Dozzle) : les logs de `php` (Apache), `db` et `logging` s'affichent en direct.

## Dépannage

| Problème | Cause et solution |
|---|---|
| `password authentication failed for user "app"` | Postgres ne lit `POSTGRES_PASSWORD` qu'à la **première création** du volume. Si le mot de passe a été modifié dans `docker-compose.yml` après coup : `docker compose down -v` puis `docker compose up -d` (⚠️ efface la base). |
| Page 500 ou erreur d'écriture dans `var/` | Vérifier `docker compose logs php`. Corriger les droits si besoin : `sudo chown -R $USER:$USER app/`, puis `docker compose restart php`. |
| `exec ... no such file or directory` au démarrage de `php` | `entrypoint.sh` a des fins de ligne Windows (CRLF). Le fichier `.gitattributes` l'évite ; sinon convertir avec `dos2unix docker/php/entrypoint.sh`, puis `docker compose up -d --build`. |
| Timeout Composer (`curl error 28`) | Problème réseau ponctuel : `docker compose restart php` relance l'installation si `vendor/` est absent. |
| Le démarrage semble bloqué | Normal au premier lancement : `composer install` tourne. Suivre `docker compose logs -f php`. |
| Port 8080, 9999 ou 5432 déjà utilisé | Modifier la partie gauche du mapping dans `docker-compose.yml` (ex. `"8081:80"`). |

## Remarques

- Les identifiants fournis sont destinés au **développement local**. Ne pas les réutiliser en production.
- Le mode Symfony par défaut est `dev` (debug activé).
- Les fichiers de `app/` sont partagés avec le conteneur : toute modification dans l'éditeur est prise en compte immédiatement.