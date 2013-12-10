<?php

/**
 * Class AuthController
 * This is example controller for authentication
 */
class AuthController extends BaseController
{
    /**
     * Process login, only for POST http method
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        /**
         * Get input values
         */
        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        );

        /**
         * Get remember me checkbox value ('on' or null)
         */
        $rememberme = Input::get('rememberme', false);

        /**
         * Check username or password is not empty
         */
        if ($credentials['username'] == '' or $credentials['password'] == '') {
            return Redirect::route('login')
                ->withInput(Input::except('password'))
                ->with('error', 'Username or password cannot be blank');
        }
        /**
         * Attempt to authenticate
         */
        if (Auth::attempt($credentials, $rememberme)) {
            /**
             * Get session variable 'redirect' for previous page
             */
            if ($url = Session::get('redirect', false)) {
                Session::forget('redirect');

                return Redirect::to($url);
            }

            return Redirect::refresh();
        }

        /**
         * If authentication failed, redirect to login page
         */

        return Redirect::route('login')
            ->withInput(Input::except('password'))
            ->with('error', 'Wrong username or password');
    }

    /**
     * Render login page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function loginPage()
    {
        /**
         * Ip address authentication.
         * Please move to closure in 'auth' filter defined in filters.php
         */
        if ($user = Auth::getProvider()->retrieveByIpAddress()) {
            Auth::login($user);
        }

        /**
         * If authentication already passed, redirect to homepage (named route 'home')
         */
        if (Auth::check()) {
            return Redirect::home();
        }

        return View::make('auth.login');
    }

    /**
     * Logout and redirect to previous page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::back();
    }
}
