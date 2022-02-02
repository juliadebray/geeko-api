## Installation

Cloner le repository
```bash
git clone git@github.com:juliadebray/geeko-api.git
```

Se rendre dans le dossier cloné
```bash
cd geeko-api
```

Créer le container Docker
```bash
docker-compose up --build -d
```

Aller dans l'image Docker
```bash
docker exec -it geeko_api bash
```

Installer les dépendances du projet
```bash
composer install
```

Générer une keypair pour les JWT
```bash
php bin/console lexik:jwt:generate-keypair
```
Créer la base de données
```bash
php bin/console doctrine:database:create
```
Charger les fixtures si besoin
```bash
php bin/console doctrine:fixtures:load
```
Voici les différentes URL disponibles:
- API: http://localhost:8080/api
- PMA: http://localhost:8081
