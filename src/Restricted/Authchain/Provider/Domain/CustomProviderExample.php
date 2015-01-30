<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Provider\Domain;

use Hash;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Provider\Provider;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Class CustomProviderExample
 *
 * @package Restricted\Authchain\Provider\Domain
 */
class CustomProviderExample extends Provider implements ProviderInterface
{

    /**
     * @inheritdoc
     */
    public function authenticate()
    {
        $users = Loader::domain($this->domain)['users'];

        if (!isset($users[$this->username])) {
            return null;
        }

        $password = $users[$this->username];

        if (Hash::check($this->password, $password)) {
            $newUser                       = $this->model();
            $newUser->{Loader::username()} = $this->username;
            $newUser->{Loader::password()} = $password;
            $newUser->enabled              = true;

            $newUser->save();

            return $newUser;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return 'custom';
    }

}