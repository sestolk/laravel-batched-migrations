# Laravel Batched Migrations
[![License](https://poser.pugx.org/sestolk/laravel-batched-migrations/license.svg)](https://packagist.org/packages/sestolk/laravel-batched-migrations)
[![Latest Stable Version](https://poser.pugx.org/sestolk/laravel-batched-migrations/v/stable.svg)](https://packagist.org/packages/sestolk/laravel-batched-migrations)
[![Total Downloads](https://poser.pugx.org/sestolk/laravel-batched-migrations/downloads.svg)](https://packagist.org/packages/sestolk/laravel-batched-migrations)

## Installation

Require this package with composer:

```
composer require sestolk/laravel-batched-migrations
```

After updating composer, add the MigrationsServiceProvider to the providers array in config/app.php

```
sestolk\BatchedMigrations\MigrationsServiceProvider::class,
```

## Configuration

Some configuration can be done by publishing the configuration file.

You _should_ use Artisan to copy the default configuration file to `/config/batched.migrations.php` with the following command:

```
php artisan vendor:publish --provider="sestolk\BatchedMigrations\MigrationsServiceProvider"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.