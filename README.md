# Laravel Batched Migrations
[![License](https://poser.pugx.org/sestolk/laravel-batched-migrations/license.svg)](https://packagist.org/packages/sestolk/laravel-batched-migrations)
[![Latest Stable Version](https://poser.pugx.org/sestolk/laravel-batched-migrations/v/stable.svg)](https://packagist.org/packages/sestolk/laravel-batched-migrations)
[![Total Downloads](https://poser.pugx.org/sestolk/laravel-batched-migrations/downloads.svg)](https://packagist.org/packages/sestolk/laravel-batched-migrations)

This package overrides the `make:migration` command and upon calling it applies a number to the end of the file when it already exists. Like so: `2016_01_01_151500_update_users_friends_1.php`

> Please note: This package and its examples are written for a PostgreSQL database. Therefore you frequently see the word **schema**. If you use MySQL or a different database type you can ommit the word **schema** and it will work just fine.

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

# Making Migrations
On making a new migration you can use the following format and you won't have to worry about duplicate class/file-names. You can keep your filenames, just plain and simple like the names below.

## Creating something new (like a table or schema)?
```
php artisan make:migration create_{schema}_{table}
```
> Replace {schema} and {table} with the schema and table you are creating

## Updating a table (like adding, removing or changing a column)?
```
php artisan make:migration update_{schema}_{table}
```
> Replace {schema} and {table} with the schema and table you are updating

## Example?

### Creating a table
If I want to create a new table **friends** in the schema **users**, I run the following command on my local environment:
```
php artisan make:migration create_users_friends
```

### Updating a table
If I want to update the existing table **friends** in the schema **users**, I run the following command on my local environment:
```
php artisan make:migration update_users_friends
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.