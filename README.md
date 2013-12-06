Laravel 4 chain authentication provider
=========

Supports native database, LDAP, IMAP multi-domain authentication for single sign-on.

For LDAP and IMAP authentication you need to have `ldap` and `imap` php extensions.


## Installation

Installing this package through Composer. Edit your project's `composer.json` file to require `restricted/authchain`.

	"require": {
		"laravel/framework": "4.0.*",
		"restricted/authchain": "1.0.*"
	},
	"minimum-stability" : "dev"

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


## Usage

Please see `app/config/packages/restricted/authchain/config.php` for configuration instructions.

You need to create User model in `app/models` and create migration.

Example migration can be executed by command `php artisan migrate --package="restricted/authchain"`.


## License

Distributed under the terms of the MIT license.