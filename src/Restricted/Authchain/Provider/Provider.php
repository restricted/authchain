<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Provider;

use Illuminate\Auth\UserInterface;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Resolver\ProviderResolver;
use Restricted\Authchain\Resolver\ResolverInterface;

/**
 * Class Provider
 *
 * @package Restricted\Authchain\Provider
 */
class Provider
{

    /**
     * @var array
     */
    protected $config;
    /**
     * @var array
     */
    protected $credentials = array();
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var string
     */
    protected $login;
    /**
     * @var UserInterface
     */
    protected $model = false;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var ResolverInterface
     */
    protected $resolver;
    /**
     * @var string
     */
    protected $username;

    /**
     * Constructor. Sets new user model.
     */
    public function __construct()
    {
        $this->userModel = Loader::user();
    }

    /**
     * @inheritdoc
     */
    public function register($user)
    {
    }

    /**
     * @inheritdoc
     */
    public function setCredentials(array $credentials)
    {
        if ($credentials) {
            $this->credentials = $credentials;
            $this->username    = $this->credentials[Loader::username()];
            if (strstr($this->username, "@")) {
                list($this->login, $this->domain) = explode("@", $this->username);
            }
            $this->password = $this->credentials[Loader::password()];
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * Create new model or return existing
     *
     * @return UserInterface|\Eloquent
     */
    protected function model()
    {
        if ($this->model) {
            return $this->model;
        }

        $class = '\\' . ltrim($this->userModel, '\\');

        return new $class;
    }
}