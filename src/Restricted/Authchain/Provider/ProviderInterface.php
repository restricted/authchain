<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Provider;

use Illuminate\Auth\UserInterface;
use Restricted\Authchain\Resolver\ResolverInterface;

/**
 * Interface ProviderInterface
 *
 * @package Restricted\Authchain\Provider
 */
interface ProviderInterface
{

    /**
     * Authenticate user
     *
     * @return UserInterface|null
     */
    public function authenticate();

    /**
     * Register user in native provider
     *
     * @param $user
     *
     * @return UserInterface|bool
     */
    public function register($user);

    /**
     * Get provider type
     *
     * @return string
     */
    public function provides();

    /**
     * Set current credentials to provider
     *
     * @param array $credentials
     *
     * @return ProviderInterface
     */
    public function setCredentials(array $credentials);

    /**
     * Set resolver
     *
     * @param ResolverInterface $resolver
     *
     * @return ProviderInterface
     */
    public function setResolver(ResolverInterface $resolver);

}