### RELMS Conf
php 8.2, Laravel 12, Apache, MariaDB 10.5
### Install once
Docker, Docker Compose
```sh
#bash into the app container
cp .env.example .env
composer install
php artisan migrate
php artisan db:seed
```
Run application
```
docker-compose up
```