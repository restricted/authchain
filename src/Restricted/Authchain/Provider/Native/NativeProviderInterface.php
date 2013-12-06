<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Provider\Native;

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
     * @return object|boolean              Return user or false
     */
    public function find($identifier);

    /**
     * Native provider can find user by field
     *
     * @param  string $key
     * @param  string $value
     *
     * @return object|boolean Return user or false
     */
    public function findBy($key, $value);

}