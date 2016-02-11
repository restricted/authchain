Laravel 4 chain authentication provider
=========

**WARNING: Project no longer maintaned!**

Supports native database, LDAP, IMAP and IP address multi-domain authentication for single sign-on.

For LDAP and IMAP authentication you need to have `ldap` and `imap` php extensions.


## Installation

Installing this package through Composer. Edit your project's `composer.json` file to require `restricted/authchain`.

	"require": {
		"laravel/framework": "4.2.*",
		"restricted/authchain": ">=1.0.6"
	}

Update Composer from the Terminal:

    composer update

Once this operation completes, the next step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.

```php
'providers' => [
    // Your Laravel providers here...
    'Restricted\Authchain\AuthchainServiceProvider'
]
```

Create example configuration file from terminal:

    php artisan config:publish restricted/authchain

**Change default authentication provider to `authchain`.**

Open `app/config/auth.php` and change `driver` section to `authchain`.

```
return array(

	'driver': 'authchain'

	// Related stuff...
);

```

Please see `app/config/packages/restricted/authchain/config.php` for full configuration instructions.

You need to create User model in `app/models` and create migration.

For details on models and migrations you can see `vendor/restricted/authchain/quickstart`

You can simply copy contents of folder `vendor/restricted/authchain/quickstart/models/` to `app/models`.

Example migration can be executed by command 
`php artisan migrate --package="restricted/authchain"`. 
> NOTE: migration does`t include timestamps.

If you don't use ip address authentication, set `['defaults']['ip']` to `false` in `app/config/packages/restricted/authchain/config.php`.

## Quickstart

Install laravel (see http://laravel.com/docs/quick)

    composer create-project laravel/laravel your-project-name --prefer-dist

Install authchain provider (see [Installation](#installation))

Configure your domains in `app/config/packages/restricted/authchain/config.php`

Copy files from

    cp -r vendor/restricted/authchain/quickstart/models/* app/models
    cp -r vendor/restricted/authchain/quickstart/controllers/* app/controllers/
    cp -r vendor/restricted/authchain/quickstart/views/* app/views

Replace `auth` route filter in your file `app/filters.php` with contents from `vendor/restricted/authchain/quickstart/filters.php`

	cat vendor/restricted/authchain/quickstart/filters.php >> app/filters.php

Add to your `app/routes.php` contents from `vendor/restricted/authchain/quickstart/routes.php`

	cat vendor/restricted/authchain/quickstart/routes.php >> app/routes.php 

Serve your application from terminal: `php artisan serve`

Go to `http://localhost:8000/` and enjoy!

## Need community feedback
- Need to implement oAuth2 ?
- Other providers?

## Contribute

### Any suggestions are welcome

You can easily write your own authentication provider for authchain:

Custom provider example (see in `src/Restricted/Authchain/Provider/Domain/CustomProviderExample`):

```php

namespace Restricted\Authchain\Provider\Domain;

use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Provider\Provider;
use Restricted\Authchain\Provider\ProviderInterface;

class CustomProviderExample extends Provider implements ProviderInterface
{
    // Authentication logic
    // $this->username is username provided by user
    // $this->password is password from form

    // @return UserInterface|null

    public function authenticate()
    {
    	// Loading users from config for domain $this->domain
    	
        $users = Loader::domain($this->domain)['users'];

	// If user not found in array, return null
	
        if (!isset($users[$this->username])) {
            return null;
        }
	
	// Grab user password from config

        $password = $users[$this->username];

	// Check password

        if (\Hash::check($this->password, $password)) {
        
            $newUser                       = $this->model();
            $newUser->{Loader::username()} = $this->username;
            $newUser->{Loader::password()} = \Hash::make($password);
            $newUser->enabled              = true;

            $newUser->save();

            return $newUser;
        }

        return null;
    }
    
    // Must return name of the provider, for example 'custom'
    // In app/config/packages/restricted/authchain/config.php
    // you can regiter new provider in 'providers' array and pass config variables to it

    public function provides()
    {
        return 'custom';
    }

}
```

Create config for custom provider in `app/config/packages/restricted/authchain/config.php`:

Register custom provider in section `providers`:

    'providers' => array(
        // ...
        'Restricted\Authchain\Provider\Domain\CustomProviderExample',
    )

In section `domains`:

    'localhost' => array(
        'provider' => 'custom', // See method provides()
            'users' => array(
                'demo@localhost' => '$2y$10$/Ij0dzDL49OaODli.1GcveefSdEapt2vgb8shplVI7RIJadPmL6km' // Encrypted password
        )
    )


Now, all users with domain `localhost` authenticates over custom provider and native provider (Eloquent).

- For questions, create issue with your question.
- For request features, create issue with detailed explanation of a feature.


## License

Distributed under the terms of the MIT license.
