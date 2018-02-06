## MagicApiPhpWrapper
[![Packagist License](https://poser.pugx.org/iarcadia/magic-api-php-wrapper/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/iarcadia/magic-api-php-wrapper/version.png)](https://packagist.org/packages/iarcadia/magic-api-php-wrapper)
[![Total Downloads](https://poser.pugx.org/iarcadia/magic-api-php-wrapper/d/total.png)](https://packagist.org/packages/iarcadia/magic-api-php-wrapper)

This package is a POO-oriented **AND** procedural API PHP wrapper. With MagicApiPhpWrapper, you can easily obtain data from online API. The package includes a built-in caching system.

## Installation

Require this package with composer:

```
composer require iarcadia/magic-api-php-wrapper
```

## POO-oriented usage

### Overview

```php
// "MAPW" stands for "Magic API PHP Wrapper"... of course!

// First, create your MAPW object with your configuration.
$api = new MAPW(
[
    'api.base_url' => 'https://www.your-awesome-url.com/api/',
    'api.key' => 'YourAwesomeApiKeyIfNeeded',
    'cache.use' => false,
    // ...
]);

// Second, get your data!
// Here, data will be from "https://www.your-awesome-url.com/api/countries":
$data = $api->get('countries');
// Here, data will be from "https://www.your-awesome-url.com/api/countries?from=Asia":
$data = $api->get('countries', ['from' => 'Asia']);
// Here, data will be from "https://www.your-awesome-url.com/api/cities?from=Spain&from=Italia":
$data = $api->get('https://www.your-awesome-url.com/api/cities?from=Spain&from=Italia');
```

Don't forget to ```include``` files or to ```use``` classes. (e.g. ```use iArcadia\MagicApiPhpWrapper\MAPW```)

## Procedural usage

### Overview

```php
// First, initialize the MAPW object with your configuration.
// In background, it will create a global variable with the newly created object : $GLOBALS['MAPW_INSTANCE'] = new MAPW(...)
mapw_initialize(
[
    'api.base_url' => 'https://www.your-awesome-url.com/api/',
    'api.key' => 'YourAwesomeApiKeyIfNeeded',
    'cache.use' => false,
    // ...
])

// Second, get your data!
// Here, data will be from "https://www.your-awesome-url.com/api/countries":
$data = mapw_get('countries');
// Here, data will be from "https://www.your-awesome-url.com/api/countries?from=Asia":
$data = mapw_get('countries', ['from' => 'Asia']);
// Here, data will be from "https://www.your-awesome-url.com/api/cities?from=Spain&from=Italia":
$data = mapw_get('https://www.your-awesome-url.com/api/cities?from=Spain&from=Italia');
```

Don't forget to ```include``` files.

## CHANGELOGS

See [CHANGELOGS.md](https://github.com/iArcadia/magic-api-php-wrapper/blob/master/CHANGELOGS.md)