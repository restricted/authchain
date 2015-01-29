<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Provider\Domain;

use Request;
use Illuminate\Auth\UserInterface;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Provider\Provider;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Class IpAddressProvider
 *
 * @package Restricted\Authchain\Provider\Domain
 */
class IpAddressProvider extends Provider implements ProviderInterface
{
    /**
     * Authenticate user
     *
     * @throws \Exception
     * @return UserInterface|bool
     */
    public function authenticate()
    {
        $ipAddress = Request::getClientIp();

        if (!($config = Loader::ip())) {
            return false;
        }

        isset($config['model']) ? $model = $config['model'] : $model = 'Ip';
        isset($config['ip_address_field']) ? $field = $config['ip_address_field'] : $field = 'address';
        isset($config['relation']) ? $relation = $config['relation'] : $relation = 'user';

        $class = '\\' . ltrim($model, '\\');

        if (!class_exists($class)) {
            throw new \Exception("Class '" . $model . "' not found for ip address authentication provider. Check config.");
        }

        $ipModel = new $class;

        /**
         * @var \Ip $ipModel
         */
        if ($exists = $ipModel->where($field, $ipAddress)->first()) {
            $user = $exists->{$relation};

            return $user;
        }

        return false;
    }

    /**
     * Get provider type
     *
     * @return string
     */
    public function provides()
    {
        return 'ip';
    }


} 