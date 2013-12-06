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
 * Class DelegatingAuthentication
 *
 * @package Restricted\Authchain\Resolver
 */
class DelegatingAuthentication
{
    /**
     * Providers chain from config
     *
     * @var array
     */
    protected $chain;
    /**
     * @var ProviderInterface
     */
    protected $provider;
    /**
     * @var ProviderResolver
     */
    protected $resolver;

    /**
     * Constructor. Sets provider resolver.
     */
    public function __construct()
    {
        $this->resolver = new ProviderResolver();
    }

    /**
     * Get provider for credentials
     *
     * @param  array $credentials
     *
     * @return ProviderInterface
     */
    public function provider($credentials)
    {
        $this->checkFields($credentials);

        if (!strstr($credentials[Loader::username()], '@')) {
            if ($this->defaultDomain()) {
                $credentials[Loader::username()] .= '@' . $this->defaultDomain();
                $this->provider = $this->resolver->get(Loader::domain($this->defaultDomain())['provider'], $credentials);
            } else {
                $this->provider = $this->native($credentials);
            }

            return $this->provider;
        }
        $domain = explode("@", $credentials[Loader::username()])[1];
        if ($this->provider = $this->resolver->get(Loader::domain($domain)['provider'], $credentials)) {
            return $this->provider;
        }

        return $this->native($credentials);
    }

    /**
     * Check credentials fields from auth config
     *
     * @param $credentials
     *
     * @throws \Exception
     */
    private function checkFields($credentials)
    {
        if (!isset($credentials[Loader::username()])) {
            throw new \Exception("Unsupported credentials array. Must define '" . Loader::username() . "' key for username", 1);
        }
        if (!isset($credentials[Loader::password()])) {
            throw new \Exception("Unsupported credentials array. Must define '" . Loader::password() . "' key for password", 1);
        }
    }

    /**
     * Get default domain for authentication without domain
     *
     * @return string|bool
     */
    private function defaultDomain()
    {
        foreach (Loader::domains() as $domain => $parameters) {
            if (isset($parameters['default']) and $parameters['default']) {
                return $domain;
            }
        }

        return false;
    }

    /**
     * Get native provider
     *
     * @param $credentials
     *
     * @return NativeProviderInterface
     */
    public function native($credentials = array())
    {
        return $this->resolver->native()->setCredentials($credentials);
    }
}