# LaravelApiService



docker-compose up -d --build

docker exec -it laravel-api001-app /bin/bash

php artisan migrate

php artisan queue:restart

php artisan queue:work --queue=requests --tries=1