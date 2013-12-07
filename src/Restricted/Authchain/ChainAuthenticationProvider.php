<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain;

use Hash;
use Session;
use Redirect;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Resolver\DelegatingAuthentication;

/**
 * Class ChainAuthenticationProvider
 *
 * @package Restricted\Authchain
 */
class ChainAuthenticationProvider implements UserProviderInterface
{
    /**
     * Delegator service
     *
     * @var DelegatingAuthentication $delegator
     */
    protected $delegator;

    /**
     * Constructor. Sets delegator.
     */
    public function __construct()
    {
        $this->delegator = new DelegatingAuthentication();
    }

    /**
     * @inheritdoc
     */
    public function retrieveByCredentials(array $credentials)
    {
        if ($user = $this->delegator->provider($credentials)->authenticate() and $this->validateCredentials($user, $credentials)) {
            return $user;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $plain = $credentials[Loader::password()];

        if (!Hash::check($plain, $user->getAuthPassword())) {
            return false;
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function retrieveById($identifier)
    {
        if ($user = $this->delegator->native()->find($identifier)) {
            return $user;
        }
        /**
         * Maybe user is removed or blocked in database but session still exists
         */
        Session::flush();

        return Redirect::refresh('302');
    }
}
