# Marshmallow - Laravel IP Access
[![marshmallow.](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")](https://marshmallow.dev)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/nova-styling.svg)](https://packagist.org/packages/marshmallow/nova-styling)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/nova-styling.svg)](https://packagist.org/packages/marshmallow/nova-styling)
[![License](https://img.shields.io/packagist/l/marshmallow/nova-styling.svg)](https://gitlab.com/marshmallowdev)
[![Stars](https://img.shields.io/github/stars/marshmallow-packages/nova-styling?color=yellow&style=plastic)](https://github.com/marshmallow-packages/nova-styling)
[![Forks](https://img.shields.io/github/forks/marshmallow-packages/nova-styling?color=brightgreen&style=plastic)](https://github.com/marshmallow-packages/nova-styling)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/ip-access.svg?style=flat-square)](https://packagist.org/packages/marshmallow/ip-access)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/marshmallow/ip-access/run-tests?label=tests)](https://github.com/marshmallow/ip-access/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/ip-access.svg?style=flat-square)](https://packagist.org/packages/marshmallow/ip-access)


This a IP Access redirect package. The purpose is to allow or deny access the Laravel routes by IP address. It is able to allow certain IPs or Users to access default files or envirioments, and redirect non-authorized IPs or Users to an external URL, while letting whitelisted IPs to have access to the entire site or to a special Envirionment.

## Installation

You can install the package via composer:

```bash
composer require marshmallow/ip-access
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Marshmallow\IpAccess\IpAccessServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Marshmallow\IpAccess\IpAccessServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$ip-access = new Marshmallow\IpAccess();
echo $ip-access->echoPhrase('Hello, Marshmallow!');
```

## Usage in Laravel Nova
Are you using Nova? We have a command for you to generate the Nova Resource. Run the commands below and the resources will be available to you in Nova. We hide the Address resource by default in the Nova navigation. If you wish to have it available in the navigation, add `public static $displayInNavigation = true;` to `app/Nova/IpAccess.php`.

```
`php artisan marshmallow:resource IpAccess`
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [LTKort](https://github.com/LTKort)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
