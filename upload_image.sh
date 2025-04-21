#!/bin/bash

# Stop execution if a step fails
set -e

IMAGE_NAME=gitlab.up.pt:5050/lbaw/lbaw2425/lbaw24025 # Replace with your group's image name

# Ensure that dependencies are available
composer install
php artisan config:clear
php artisan clear-compiled
php artisan optimize

docker buildx build --push --platform linux/amd64,linux/arm64 -t $IMAGE_NAME .
