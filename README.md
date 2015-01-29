Laravel 4 chain authentication provider
=========

Supports native database, LDAP, IMAP and ip address multi-domain authentication for single sign-on.

For LDAP and IMAP authentication you need to have `ldap` and `imap` php extensions.


## Installation

Installing this package through Composer. Edit your project's `composer.json` file to require `restricted/authchain`.

For Laravel 4.2 you need to add:

	"require": {
		"laravel/framework": "4.2.*",
		"restricted/authchain": "=>1.0.6"
	}

For Laravel 4.0 and 4.1:

	"require": {
		"laravel/framework": "4.X.*",
		"restricted/authchain": "1.0.5"
	}


Update Composer from the Terminal:

    composer update

Once this operation completes, the next step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

```php
'providers' => [
    // ..
    'Restricted\Authchain\AuthchainServiceProvider'
]
```

Create example configuration file from terminal:

    php artisan config:publish restricted/authchain

Finally, change default authentication provider to `authchain`. Open `app/config/auth.php` and change `driver` section to `authchain`.

Please see `app/config/packages/restricted/authchain/config.php` for configuration instructions.

You need to create User model in `app/models` and create migration.
For details on models and migrations you can see `vendor/restricted/authchain/quickstart`

Simply copy contents of folder `vendor/restricted/authchain/quickstart/models/` to `app/models`.
Example migration can be executed by command `php artisan migrate --package="restricted/authchain"`. NOTE: migration does`t include timestamps.

If you don't use ip address authentication, set `['defaults']['ip']` to `false` in `app/config/packages/restricted/authchain/config.php`.

## Quickstart

Install laravel (see http://laravel.com/docs/quick)

    composer create-project laravel/laravel your-project-name --prefer-dist

Install authchain provider (see [Installation](#installation))

Configure your domains in `app/config/packages/restricted/authchain/config.php`

Copy files from
    `vendor/restricted/authchain/quickstart/models/` to `app/models`
    `vendor/restricted/authchain/quickstart/controllers/` to `app/controllers`
    `vendor/restricted/authchain/quickstart/views/` to `app/views`

Add to your `app/filters.php` contents from `vendor/restricted/authchain/quickstart/filter.php`

Add to your `app/routes.php` contents from `vendor/restricted/authchain/quickstart/routes.php`

Serve your application from terminal: `php artisan serve`

Go to `http://localhost:8000/` and enjoy!


## License

Distributed under the terms of the MIT license.
