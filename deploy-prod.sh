#!/usr/bin/env bash

# Récupérer le code source
git pull origin master

# Récupère les librairies
composer install --no-dev

# Vide le cache
drush cr

# Mise-à-jour BDD Drupal
drush updb -y

# Exporte les config de prod (merci Webform)
drush csex prod -y

# Importe les configs
drush cim -y

# Vide le cache
drush cr