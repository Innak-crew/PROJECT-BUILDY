#!/bin/bash

# (Optional) Perform any custom build steps specific to your application

# Clear Laravel cache
php artisan cache:clear

# Clear application storage (if applicable)
# php artisan storage:link

# Migrate database (if applicable)
# php artisan migrate

# Run any other commands specific to your deployment process

echo "Deployment completed!"
