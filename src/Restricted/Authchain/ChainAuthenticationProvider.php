<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain;

use Hash;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
use Redirect;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Resolver\DelegatingAuthentication;
use Session;

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
        $identifier = Loader::username();
        $username   = $credentials[$identifier];
        if ($user = $this->delegator->native()->findBy($identifier, $username)) {
            return $user;
        }

        if ($user = $this->delegator->provider($credentials)->authenticate() and $this->validateCredentials($user, $credentials)) {
            return $user;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $plain = $credentials[Loader::password()];
        if (Hash::check($plain, $user->getAuthPassword())) {
            return true;
        }

        if ($this->delegator->provider($credentials)->authenticate()) {
            return true;
        }

        return null;
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

    /**
     * Retrieve user by ip address
     *
     * @return null|UserInterface
     */
    public function retrieveByIpAddress()
    {
        return $this->delegator->resolver()->get('ip')->authenticate();
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string $token
     *
     * @return UserInterface|null
     */
    public function retrieveByToken($identifier, $token)
    {
        if ($user = $this->delegator->native()->findByToken($identifier, $token)) {
            return $user;
        }

        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  UserInterface $user
     * @param  string        $token
     *
     * @return void
     */
    public function updateRememberToken(UserInterface $user, $token)
    {
        $user->{$user->getRememberTokenName()} = $token;
        $user->save();
    }
}
