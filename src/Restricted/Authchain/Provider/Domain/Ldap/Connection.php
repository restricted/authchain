<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Provider\Domain\Ldap;

use Log;

/**
 * Class Connection
 *
 * @package Restricted\Authchain\Provider\Domain\Ldap
 */
class Connection
{

    /**
     * Constructor. Check ldap extension is loaded.
     */
    public function __construct()
    {
        if (!extension_loaded('ldap')) {
            throw new \Exception("Cannot use LDAP provider without ldap module.", 1);
        }
    }

    /**
     * @var resource
     */
    protected $connection;

    /**
     * Bind to ldap server
     *
     * @param $username
     * @param $password
     *
     * @return bool
     */
    public function bind($username, $password)
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
     * Connect to ldap server
     *
     * @param $hosts
     *
     * @return bool|resource
     */
    public function connect(array $hosts)
    {
        foreach ($hosts as $name => $address) {
            $port = 389;
            if (strstr($address, ":")) {
                list($address, $port) = explode(":", $address);
            }
            try {
                $this->connection = @ldap_connect($address, $port);
                ldap_set_option($this->connection, LDAP_OPT_NETWORK_TIMEOUT, 2);
                ldap_set_option($this->connection, LDAP_OPT_TIMELIMIT, 2);
                ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);

                return $this->connection;
            } catch (\Exception $e) {
                Log::warning('[LDAP] Cannot connect to ' . $name . ': ' . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * Search entry in ldap server
     *
     * @param $baseDn
     * @param $mappings
     * @param $filter
     *
     * @return array|bool
     */
    public function searchEntry(array $baseDn, $mappings, $filter)
    {
        $search = $this->search($baseDn, $mappings, $filter);
        if (isset($search[0])) {
            return $search[0];
        }

        return false;

    }

    /**
     * Get all ldap search entries
     *
     * @param array $baseDn
     * @param       $mappings
     * @param       $filter
     *
     * @return array|bool
     */
    public function search(array $baseDn, $mappings, $filter)
    {
        foreach ($baseDn as $dn) {
            $attributes = $mappings === 'auto' ? $attributes = array() : array_values($mappings['fields']);
            $search     = @ldap_search($this->connection, $dn, $filter, $attributes, false, 0, 0, LDAP_DEREF_ALWAYS);
            $find       = @ldap_get_entries($this->connection, $search);
            if ($find) {
                return $find;
            }
        }

        return false;
    }
} 