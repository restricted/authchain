<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Provider\Domain;

use Log;
use Crypt;
use Hash;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Mapping\LdapMapping;
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
        if (!extension_loaded('ldap')) {
            throw new \Exception("Cannot use LDAP provider without ldap module.", 1);
        }

        $this->config     = Loader::domain($this->domain);
        $this->connection = $this->connect();

        if (!$this->bind($this->username, $this->password)) {
            return false;
        }

        $user = $this->search('samaccountname=' . $this->login);

        if (!$user) {
            throw new \Exception("User not found by provided DN`s. Check your settings.", 1);
        }

        return $this->register($user);
    }

    /**
     *
     * @return bool|resource
     */
    private function connect()
    {
        foreach ($this->config['hosts'] as $name => $address) {
            $port = 389;
            if (strstr($address, ":")) {
                list($address, $port) = explode(":", $address);
            }
            try {
                $connection = @ldap_connect($address, $port);
                ldap_set_option($connection, LDAP_OPT_NETWORK_TIMEOUT, 2);
                ldap_set_option($connection, LDAP_OPT_TIMELIMIT, 2);
                ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

                return $connection;
            } catch (\Exception $e) {
                Log::warning('[LDAP] Cannot connect to ' . $name . ': ' . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * @param $username
     * @param $password
     *
     * @return bool
     */
    private function bind($username, $password)
    {
        if (!$this->connection) {
            return false;
        }
        if (!@ldap_bind($this->connection, $username, $password)) {
            return false;
        }

        return true;
    }

    /**
     * @param $filter
     *
     * @return array|bool
     */
    protected function search($filter)
    {
        foreach ($this->config['baseDN'] as $dn) {
            $attributes = $this->config['mappings'] === 'auto' ? $attributes = array() : array_values($this->config['mappings']['fields']);
            $search     = @ldap_search($this->connection, $dn, $filter, $attributes, false, 0, 0, LDAP_DEREF_ALWAYS);
            $entry      = @ldap_first_entry($this->connection, $search);
            $find       = @ldap_get_attributes($this->connection, $entry);
            if ($find) {
                return $find;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function register($user)
    {
        if ($find = $this->resolver->native()->findBy(Loader::username(), $this->username)) {
            $this->model = $find;
        }
        $mapping                     = new LdapMapping($this->config['mappings']);
        $user[Loader::password()][0] = Crypt::encrypt(Hash::make($this->password));

        return $mapping->map($user, $this->model());
    }

    /**
     * Get canonical name from dn
     *
     * @param $cn
     *
     * @return mixed
     */
    protected function describeCN($cn)
    {
        return explode(',', $cn)[0];
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return 'ldap';
    }

}