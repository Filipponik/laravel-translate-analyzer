# Translate analyzer for Laravel

## Installing

Install package

```shell
composer require filipponik/laravel-translate-analyzer --dev
```

Add service provider to your `config/app.php` file:
```php
...
    'providers' => [
        ...
        \Filipponik\LaravelTranslateAnalyzer\Providers\TranslateServiceProvider::class,
    ],
...
```

## Usage

If you want to get duplicated values in your lang files, use command below:
```shell
php artisan translate:check-duplicates
```

If you want to analyze your code and fill lang files, use command below:
```shell
php artisan translate:fill-files
```
