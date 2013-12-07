<?php

/**
 * Class AuthController
 */
class AuthController extends BaseController
{
    public function login()
    {
        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        );
        if ($credentials['username'] == '' or $credentials['password'] == '') {
            return Redirect::route('login')
                ->withInput(Input::except('password'))
                ->with('error', 'Username or password cannot be blank');
        }
        if (Auth::attempt($credentials)) {
            return Redirect::refresh();
        }

        return Redirect::route('login')
            ->withInput(Input::except('password'))
            ->with('error', 'Wrong username or password');
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
