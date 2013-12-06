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

Please see `app/config/packages/restricted/authchain/config.php` for configuration instructions.

You need to create User model in `app/models` and create migration.

You ``must`` change method `getAuthPassword()` to:

```php
public function getAuthPassword()
    {
        return Crypt::decrypt($this->password);
    }
```
Or simply copy model from `vendor/restricted/authchain/src/models/` to `app/models`.

Example migration can be executed by command `php artisan migrate --package="restricted/authchain"`. NOTE: migration does`t include timestamps.

## Quickstart

Install laravel (see http://laravel.com/docs/quick)

    composer create-project laravel/laravel your-project-name --prefer-dist

Install authchain provider (see [Installation](#installation))

Configure your domains in `app/config/packages/restricted/authchain/config.php`

Change file `app/models/User.php`:

```php
class User extends Eloquent implements UserInterface, RemindableInterface {
    ..
    public $timestamps = false;
    ..
    public function getAuthPassword() {

        return Crypt::decrypt($this->password);

    }
}
```

Add routes in `app/routes.php`:

```php
Route::filter('guest', function () { if (Auth::check()) { return Redirect::route('home'); }});
Route::filter('auth', function () { if (Auth::guest()) { return Redirect::route('login')->with('flash_notice', 'You are already logged in!'); }});

Route::post('login', 'AuthController@login');
Route::get('login', array('as'=>'login', 'uses'=>'AuthController@loginPage'));
Route::get('logout', array('as'=>'logout', 'uses'=>'AuthController@logout'));
Route::get('/', array('before' => 'auth', 'as' => 'home', function () { return View::make('hello'); }));
```

Create AuthController.php in `app/controllers` with:

```php
class AuthController extends BaseController
{
    public function login()
    {
        $credentials = array('username' => Input::get('username'),'password' => Input::get('password'));
      	if ($credentials['username'] == '' or $credentials['password'] == '') {
      	    return Redirect::route('login')
                ->withInput(Input::except('password'))
                ->with('error', 'Username or password cannot be blank');
       	}
        if (Auth::attempt($credentials)) {
            return Redirect::refresh();
        }
        return Redirect::route('login')->withInput(Input::except('password'))->with('error', 'Wrong username or password');
    }

    public function loginPage()
    {
        if (Auth::check()) {
            return Redirect::home();
        }
        return View::make('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::back();
    }
}
```

Create view `auth.login` in `app/views`, `app/views/auth/login.blade.php` with:

```html
<h1>Login page</h1>
{{ $error = Session::get('error') }}
@if($error)
<h2>{{ $error }}</h2>
@endif
{{ Form::open(['login']) }}
{{ Form::input('text', 'username', 'username') }}
{{ Form::password('password') }}
{{ Form::submit('Submit') }}
{{ Form::close() }}
```

Rename `app/views/hello.php` to `app/views/hello.blade.php` and add after `<h1>You have arrived.</h1>`:

```html
@if ( Auth::check() )
    Logged in as {{ Auth::user()->username }}<br />
    {{ HTML::link(route('logout'), 'Logout') }}
@endif
```

Serve your application from terminal: `php artisan serve`

Go to `http://localhost:8000/` and enjoy!


## License

Distributed under the terms of the MIT license.