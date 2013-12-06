<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Mapping;

use Illuminate\Auth\UserInterface;

/**
 * Class LdapMapping
 *
 * @package Restricted\Authchain\Mapping
 */
class LdapMapping
{
    /**
     * Windows Active Directory userAccountControl variables if user is locked
     *
     * @var array
     */
    protected $locked = array(
        "PASSWORD_EXPIRED"   => 8388608,
        "NOT_DELEGATED"      => 1048576,
        "SMARTCARD_REQUIRED" => 262144,
        "LOCKOUT"            => 514,
        "ACCOUNTDISABLE"     => 2
    );

    /**
     * Constructor. Sets mappings from config.
     *
     * @param $mappings
     */
    public function __construct($mappings)
    {
        $this->mappings = $mappings;
    }

    /**
     * Map ldap user to model
     *
     * @param array                   $ldap
     * @param UserInterface|\Eloquent $model
     *
     * @return mixed
     */
    public function map($ldap, UserInterface $model)
    {
        foreach ($this->mappings['fields'] as $field => $mapped) {
            if (!isset($ldap[$mapped])) {
                continue;
            }
            if ($mapped == 'userAccountControl') {
                if (!in_array($ldap[$mapped][0], $this->locked)) {
                    $ldap[$mapped][0] = true;
                } else {
                    $ldap[$mapped][0] = false;
                }
            }
            $model->$field = $ldap[$mapped][0];
        }
        $model->save();

        return $model;
    }

}