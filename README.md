# Exporo Partnerprogramm

Das Partnerprogramm ist die Affiliate-Plattform von Exporo.
Für Bestimmte Aktionen, wie zB Kunden werben, können Partner Provision bekommen.

## Schnellstart
Installiert sein müssen:
- Docker + docker-compose
- node
- yarn
- composer

### Installation
```sh
git clone git@bitbucket.org:exporodev/exporo_pp.git exporo_pp && \
cd exporo_pp/application && \
cp .env.example .env && \
yarn && yarn dev && \
composer install && \
docker-compose up -d && \
docker-compose exec webapp php artisan key:generate && \
docker-compose exec webapp php artisan config:clear && \
docker-compose exec webapp php artisan migrate:fresh --seed
```

### Fertig
[Home-Page](https://localhost/home) aufrufen und mit mit folgenden Daten anmelden:

Feld     | Wert
---------|--------------
E-Mail   | pp@exporo.de
Passwort | secret

## Tech-Stack
- Docker mit docker-compose für lokale Entwicklung sowie die Live-Server (AWS Fargate)
- Frameworks: Laravel, VueJS, Bootstrap 4

## Funktionsumfang
- Provisionsberechnung
- Ausgiebige Berechtigungsverwaltung (alles über Permissions geregelt, auch Feature-Flags)
- Komplette Verwaltung jeglicher Resourcen, z.B:
  - Projekte
  - Benutzer
  - Abrechnungen
  
## Deploy to Stage
- Einfach den 'Stage' Branch pushen
- in CircleCi wird dann eine Pipeline gestartet, die bei 'Hold' stoppt.
    - https://app.circleci.com/pipelines/bitbucket/exporodev
- Stage:
    - https://stage.partnerprogramm.exporo.de/ 
    
## Deploy to Prod
- Branch pushen
- PR erstellen  
    - https://bitbucket.org/exporodev/exporo_pp/pull-requests/new
    - Ziel: Master
    - Close Branch: anwählen
- Es wird automatisch auf prod deployed, sobald der PR bestätigt wurde.  
 
## Code Analyse
Code Style Check. Vor dem PR auszuführen.
```
php artisan code:analyse
```
