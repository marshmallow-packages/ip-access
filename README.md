# Marshmallow - Laravel IP Access
[![marshmallow.](https://marshmallow.dev/cdn/media/logo-red-237x46.png "marshmallow.")](https://marshmallow.dev)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marshmallow/ip-access.svg)](https://packagist.org/packages/marshmallow/ip-access)
[![Total Downloads](https://img.shields.io/packagist/dt/marshmallow/ip-access.svg)](https://packagist.org/packages/marshmallow/ip-access)
[![License](https://img.shields.io/packagist/l/marshmallow/ip-access.svg)](https://gitlab.com/marshmallowdev)
[![Stars](https://img.shields.io/github/stars/marshmallow-packages/ip-access?color=yellow&style=plastic)](https://github.com/marshmallow-packages/ip-access)
[![Forks](https://img.shields.io/github/forks/marshmallow-packages/ip-access?color=brightgreen&style=plastic)](https://github.com/marshmallow-packages/ip-access)

This a IP Access redirect package for IPv4 & IPv6.
The purpose is to allow or deny access to the Laravel routes by IP address.
It is able to allow certain IPs (IPv4 or IPv6) to access default files or envirioments, and redirect non-authorized IPs or Users to an external URL, while letting whitelisted IPs to have access to the entire site or to a special Envirionment.

## Installation

You can install the package via composer:

```bash
composer require marshmallow/ip-access
```

And publish the service provider (and config):
```bash
php artisan vendor:publish --provider="Marshmallow\IpAccess\IpAccessServiceProvider"
```

This is the contents of the published config file:

```php
return [
    'enabled' => env('IPACCESS_ENABLED', true),

    'whitelist_env' => env('IPACCESS_ENV', 'production'),

    'whitelist' => [
        'range' => [
            '127.0.0.*',
        ],
        'list' => [
            '127.0.0.1',
        ]
    ],

    'redirect_to'      => env('IPACCESS_DENIED_URL', null),
    'response_status'  => env('IPACCESS_DENIED_STATUS', 403),
    'response_message' => env('IPACCESS_DENIED_MESSAGE', 'Access not Allowed'),
];
```

## Optional
These are the optional .env variables to set up:
```
    # ENABLED
    IPACCESS_ENABLED=true

    # ENV THAT IS CHECKED BY IP e.g. staging
    IPACCESS_ENV=production

    # ADDIOTNAL IP LIST
    IPACCESS_WHITELIST="127.0.0.1,123.456.789.12"   # SEPERATED BY ,

    # URL TO REDIRECT TO:
    IPACCESS_DENIED_URL="https://marshmallow.dev"

    # IF URL NOT SET
    IPACCESS_DENIED_STATUS=403                      # REDIRECT STATUS
    IPACCESS_DENIED_MESSAGE="Not allowed"           # REDIRECT STATUS MESSAGE
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
