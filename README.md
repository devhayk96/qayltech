<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400">
    </a>
</p>

## About QaylTech

QaylTech is a startup focused on producing lower-limb prosthetics for people with complete or partial gait functional loss. Within the last 2 years, QaylTech has designed 9 rehabilitation devices including standing frames, tilt tables, walk simulator services, wheelchair electrical connector, etc. The products have already been utilized by hundreds of users.


## Installation

- copy .env.example .env (Windows) or cp .env.example .env (Linux)
- composer install
- php artisan key:generate
- php artisan vendor:publish --tag=passport-config
- php artisan vendor:publish --tag=passport-migrations
- php artisan passport:install
- php artisan passport:client --personal --no-interaction
- php artisan migrate --seed

