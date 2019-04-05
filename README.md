## Date Range Filter for Laravel Nova

Nova filter that displays a Date Range Picker instead of a select.

### Install

Run this command in your nova project:
`composer require ampeco/nova-date-range-filter`

### How to use

Just use DateRangeFilter class instead of Filter

```php
use Ampeco\Filters\DateRangeFilter;

class DateRange extends DateRangeFilter
{
  $from = Carbon::parse($value[0]);
  $to = Carbon::parse($value[1]);
  //
  return $query;
}
```

### Customization

Use fluent interface to configure your DateRange filter

```php

(new DateRange)->placeholder("Placeholder")->dateFormat("m d Y")

```
