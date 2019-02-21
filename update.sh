#! /bin/sh

cd /var/www &
git stash &
git pull &
chmod 777 -R ../www &
php bin/console cache:clear &
chmod 777 -R ../www