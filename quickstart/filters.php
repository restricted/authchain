
/**
 * Example authentication filter with ip address authentication check
 *
 * If you want to see in action, copy it to your app/filters.php
 */

Route::filter('auth', function()
{
    /**
     * If page need authenticated user, save url for later
     */
    Session::put('redirect', URL::current());

    /**
     * Ip address authentication
     */
    if ($user = Auth::getProvider()->retrieveByIpAddress()) {
        Auth::login($user);
    }

    /**
     * Redirect to login page
     */
    if (Auth::guest()) {
        return Redirect::route('login');
    }
});