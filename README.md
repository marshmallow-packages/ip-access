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

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Marshmallow\IpAccess\IpAccessServiceProvider" --tag="config"
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
