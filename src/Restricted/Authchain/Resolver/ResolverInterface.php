<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Resolver;

use Restricted\Authchain\Provider\Native\NativeProviderInterface;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Interface ResolverInterface
 *
 * @package Restricted\Authchain\Resolver
 */
interface ResolverInterface
{
    /**
     * @param       $type
     * @param array $credentials
     *
     * @return ProviderInterface
     */
    public function get($type, $credentials = array());

    /**
     * @return NativeProviderInterface
     */
    public function native();

    /**
     * @param ProviderInterface $provider
     */
    public function register(ProviderInterface $provider);
}