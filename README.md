# Awesome Helper for Laravel Development

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kedeka/support.svg?style=flat-square)](https://packagist.org/packages/kedeka/support)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/kedeka/support/run-tests?label=tests)](https://github.com/kedeka/support/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/kedeka/support/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/kedeka/support/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kedeka/support.svg?style=flat-square)](https://packagist.org/packages/kedeka/support)

Awesome Helper for Laravel Development

## Installation

You can install the package via composer:

```bash
composer require kedeka/support
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="support-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="support-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="support-views"
```

## Usage

```php
$support = new Kedeka\Support();
echo $support->echoPhrase('Hello, Kedeka!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/riskihajar/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rizky Hajar](https://github.com/riskihajar)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
