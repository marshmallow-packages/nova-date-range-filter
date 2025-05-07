![alt text](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")
<br/>
[![Version](https://img.shields.io/packagist/v/marshmallow/nova-date-range-filter)](https://github.com/marshmallow-packages/nova-date-range-filter)
[![Issues](https://img.shields.io/github/issues/marshmallow-packages/nova-date-range-filter)](https://github.com/marshmallow-packages/nova-date-range-filter)
[![Licence](https://img.shields.io/github/license/marshmallow-packages/nova-date-range-filter)](https://github.com/marshmallow-packages/nova-date-range-filter)

## Date Range Filter for Laravel Nova

Nova filter that displays a Date Range Picker instead of a select. This field also supports dark-mode, Nova 4 and Nova 5.

### Install

Run this command in your nova project:

```bash
composer require marshmallow/nova-date-range-filter
```

### How to use

Add the DateRangeFilter to your resource filters array.

```php
public function filters(Request $request)
{
    return [
        new \Marshmallow\Filters\DateRangeFilter('created_at', 'Created date'),
    ];
}
```

### Methods

#### addRange

With this method, you can set default date ranges that you use frequently. For instance ranges like today, yesterday, this month etc. Please check the example below.

```php
(new DateRangeFilter('created_at', 'Created date'))
    ->addRange('Today', now()->startOfDay(), now()->endOfDay())
    ->addRange('Yesterday', now()->subDay()->startOfDay(), now()->subDay()->endOfDay())
    ->addRange('This week', now()->startOfWeek(), now()->endOfWeek())
    ->addRange('Last 7 days', now()->subDays(7)->startOfDay(), now()->endOfDay())
    ->addRange('Last 30 days', now()->subDays(30)->startOfDay(), now()->endOfDay())
    ->addRange('This month', now()->startOfMonth(), now()->endOfMonth())
    ->addRange('Last month', now()->startOfMonth()->subMonth(1), now()->startOfMonth()->subMonth(1)->endOfMonth())
    ->addRange('This year', now()->startOfYear(), now()->endOfYear()),
```

#### showMonths

With this method, it will be possible to show more then just the current month.

#### enableTime (beta)

Enable the time selector when you select your date.

#### minTime (beta)

Set a min time that can be selected.

#### maxTime (beta)

Set a max time that can be selected.

### Localization

The package supports localization for all text elements. The following translations are available out of the box:

-   English (en)
-   Dutch (nl)
-   Ukrainian (uk)

If your language doesn't work in the calendar interface (using Flatpickr localization) itself, please let us know and we will add your language.

#### Customizing Translations

You can publish the translation files to customize them for your application:

```bash
php artisan vendor:publish --tag=nova-date-range-filter-translations
```

This will copy the translation files to your application's `resources/lang/vendor/nova-date-range-filter` directory, where you can modify them or add new languages.

## Security

If you discover any security related issues, please email stef@marshmallow.dev instead of using the issue tracker.

## Credits

-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
