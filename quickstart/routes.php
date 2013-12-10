<?php

/**
 * Example authentication routes
 * If you want to see in action copy it to your app/routes.php
 */

/**
 * Render login page
 */
Route::get('login', array('as' => 'login', 'uses' => 'AuthController@loginPage'));

/**
 * Process authentication
 */
Route::post('login', array('before' => 'csrf', 'uses' => 'AuthController@login'));

/**
 * Process logout
 */
Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@logout'));

/**
 * And home route must be named
 */
Route::get('/', array('as' => 'home', function() { return View::make('hello'); }));