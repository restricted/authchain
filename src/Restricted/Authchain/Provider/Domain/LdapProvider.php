<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Provider\Domain;

use Crypt;
use Hash;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Mapping\LdapMapping;
use Restricted\Authchain\Provider\Domain\Ldap\Connection;
use Restricted\Authchain\Provider\Provider;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Class LdapProvider
 *
 * @package Restricted\Authchain\Provider\Domain
 */
class LdapProvider extends Provider implements ProviderInterface
{
    /**
     * @var resource
     */
    protected $connection;

    /**
     * @inheritdoc
     */
    public function authenticate()
    {

        $this->config = Loader::domain($this->domain);
        $ldap         = new Connection();
        $ldap->connect($this->config['hosts']);

        if ($find = $this->resolver->native()->findBy(Loader::username(), $this->username)) {
            $this->model                       = $find;
            $this->model->{Loader::password()} = null;
            $this->model->save();
        }

        if (!$ldap->bind($this->username, $this->password)) {
            return false;
        }

        $user = $ldap->searchEntry($this->config['baseDN'], $this->config['mappings'], 'samaccountname=' . $this->login);
        if (!$user) {
            throw new \Exception("User not found by provided DN`s. Check your settings.", 1);
        }

        return $this->register($user);
    }

    /**
     * @inheritdoc
     */
    public function register($user)
    {
        $mapping                     = new LdapMapping($this->config['mappings']);
        $user[Loader::password()][0] = Crypt::encrypt(Hash::make($this->password));

        return $mapping->map($user, $this->model());
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return 'ldap';
    }

}