# Jeu-Crediteo
Test technique pour un entretien d'embauche chez Créditéo.

## Installation
Installer les dépendances du serveur :
```
cd server/
composer install
```
Lancer le serveur de test :
```
php -S 127.0.0.1:8080 -t public
```
Ouvrir le fichier `client/index.html` dans un navigateur.

## Informations
Pour ne pas créer de base de données j'ai choisi de sauvegarder les personnages de la partie courante dans un fichier json en local sur la machine qui fait tourner le serveur. Je suis bien conscient que ce n'est pas une solution viable dans un cas réel. C'est juste la solution la plus simple que j'ai trouvée pour conserver des données entre mes appels à l'API.
Il faudrait faire de la validation au niveau du nom fourni par l'utilisateur car l'API actuelle est exposé à l'injection. Je n'ai pas pris le temps de le faire car j'ai déjà passé beaucoup plus de temps que ce que l'on m'avait donné à la base mais je suis conscient que la faille est présente.
