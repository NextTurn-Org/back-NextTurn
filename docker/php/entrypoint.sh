#!/bin/sh
set -e
cd /var/www/html

# Propriétaire du dossier monté (ton utilisateur WSL, en général)
OWNER_UID=$(stat -c '%u' .)
OWNER_GID=$(stat -c '%g' .)

if [ "$OWNER_UID" != "0" ]; then
  # Crée le groupe et l'utilisateur dans le conteneur s'ils n'existent pas
  if ! getent group "$OWNER_GID" >/dev/null; then
    groupadd -g "$OWNER_GID" appgroup
  fi
  if ! getent passwd "$OWNER_UID" >/dev/null; then
    useradd -u "$OWNER_UID" -g "$OWNER_GID" -M -s /usr/sbin/nologin appuser
  fi

  # Apache tourne avec cet utilisateur : il peut écrire dans var/cache et var/log
  export APACHE_RUN_USER=$(getent passwd "$OWNER_UID" | cut -d: -f1)
  export APACHE_RUN_GROUP=$(getent group "$OWNER_GID" | cut -d: -f1)
fi

run_as_owner() {
  if [ "$OWNER_UID" != "0" ]; then
    setpriv --reuid="$OWNER_UID" --regid="$OWNER_GID" --clear-groups \
      env HOME=/tmp COMPOSER_HOME=/tmp/composer "$@"
  else
    "$@"
  fi
}

# Installation automatique des dépendances au premier démarrage
if [ -f composer.json ] && [ ! -d vendor ]; then
  echo ">> vendor/ absent : composer install"
  run_as_owner composer install --no-interaction --prefer-dist
fi

exec docker-php-entrypoint "$@"