# Currency support for nettoweb CMS

This software provides currency support for nettoweb CMS, including exchange rates calculator.

## Installation

Change to your Laravel project directory and run: 

```shell
composer require nettoweb/laravel-cms-currency
```

Apply database migrations:

```shell
php artisan migrate
```
Publish assets:

```shell
php artisan vendor:publish --tag=nettoweb-laravel-cms-currency
```

## Licensing

This project is licensed under MIT license.
