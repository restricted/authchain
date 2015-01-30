<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Provider\Native;

use Illuminate\Auth\UserInterface;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Interface NativeProviderInterface
 *
 * @package Restricted\Authchain\Provider\Native
 */
interface NativeProviderInterface extends ProviderInterface
{

    /**
     * Native provider can find user by id
     *
     * @param  integer|string $identifier User identifier
     *
     * @return UserInterface|null              Return user or false
     */
    public function find($identifier);

    /**
     * Native provider can find user by field
     *
     * @param  string $key
     * @param  string $value
     *
     * @return UserInterface|null Return user or false
     */
    public function findBy($key, $value);

    /**
     * Find user by username and remember_me token
     *
     * @param string $identifier
     * @param string $token
     *
     * @return UserInterface|null
     */
    public function findByToken($identifier, $token);

}