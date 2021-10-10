## Установка (Docker)
```
git clone https://github.com/KaptainMidnight/volga-it-filler-game
docker-compose up --build
```
http://127.0.0.1:8000/

## Установка локально
```
git clone https://github.com/KaptainMidnight/volga-it-filler-game
cd 7-colors-game
composer install
cp .env.example .env
touch database/7_colors_game.sqlite
php artisan migrate --seed
php artisan serve
```

* [Composer](https://getcomposer.org/) (Linux)
* [OpenServer](https://ospanel.io/) (Windows)
