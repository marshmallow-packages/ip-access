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

## Usage with Laravel Nova

If you want to keep track of the ip addresses that have access using Laravel Nova you need to follow the following steps.

### Update your config file

Set `use_nova` to true in `config/ip-access.php`.

```php
return [
    'use_nova' => true,
];
```

### Run migrations

You need to run the migrations after you've update the config file so we have the tables we need to store the ip addresse.

```bash
php artisan migrate
```

### Publish the Nova resource

The last step is publishing the Nova resource so you can manage all ip address in your Laravel Nova installation.

```bash
php artisan marshmallow:resource IpAccess IpAccess
```

## Uninstall

Once your application is done and you are going to publish your application to production for the whole world to see, you can delete this package. You don't need it anymore and its always good practice to keep your code clean. Run the command below to uninstall this package. This command will delete the `config` file. Delete the `nova resource`, delete the `migration` record, delete the `ip_accesss` database table and remove the package from your `composer` file. You will have to review and commit the changes in you GIT repository yourself.

```bash
php artisan ip-access:uninstall
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [LTKort](https://github.com/LTKort)
-   [Stef van Esch](https://github.com/stefvanesch)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
