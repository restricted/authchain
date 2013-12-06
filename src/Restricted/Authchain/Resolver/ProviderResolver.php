<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Resolver;

use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Provider\Native\NativeProviderInterface;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Class ProviderResolver
 *
 * @package Restricted\Authchain\Resolver
 */
class ProviderResolver implements ResolverInterface
{
    /**
     * @var ProviderInterface[]
     */
    protected $chain = array();

    public function __construct()
    {
        foreach (Loader::providers() as $class) {
            self::register(new $class);
        }
    }

    /**
     * Register provider
     *
     * @param ProviderInterface $provider
     */
    public function register(ProviderInterface $provider)
    {
        $this->chain[$provider->provides()] = $provider;
    }

    /**
     * Get native provider from chain
     *
     * @return bool|NativeProviderInterface
     */
    public function native()
    {
        return $this->get(Loader::native());
    }

    /**
     * Get provider by type
     *
     * @param string $type
     * @param array  $credentials
     *
     * @throws \Exception
     * @return bool|ProviderInterface
     */
    public function get($type, $credentials = array())
    {
        if (isset($this->chain[$type])) {
            return $credentials ? $this->chain[$type]->setCredentials($credentials)->setResolver($this) : $this->chain[$type]->setResolver($this);
        } else {
            throw new \Exception('Cannot find provider for type ' . $type);
        }
    }
}