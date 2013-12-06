<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain;

use Illuminate\Auth\Guard;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthchainServiceProvider
 *
 * @package Restricted\Authchain
 */
class AuthchainServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('restricted/authchain');

        \Auth::extend(
            'authchain',
            function ($app) {
                return new Guard(new ChainAuthenticationProvider(), $app->make('session.store'));
            }
        );

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('authchain');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

}