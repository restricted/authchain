<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Provider\Native;

use Hash;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Provider\Provider;

/**
 * Class EloquentProvider
 *
 * @package Restricted\Authchain\Provider\Native
 */
class EloquentProvider extends Provider implements NativeProviderInterface
{

    /**
     * @inheritdoc
     */
    public function authenticate()
    {
        if ($user = $this->findBy(Loader::username(), $this->username)) {
            if (Hash::check($this->password, $user->getAuthPassword())) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function findBy($key, $value)
    {
        $connections = Loader::connections();
        if ($connections) {
            return $this->findByKeyValueIn($connections, $key, $value);
        }

        return $this->findByKeyValue(Loader::defaultConnection(), $key, $value);
    }

    /**
     * @param $connections
     * @param $key
     * @param $value
     *
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed|null|static
     */
    private function findByKeyValueIn($connections, $key, $value)
    {
        foreach ($connections as $connection) {
            if ($user = $this->findByKeyValue($connection, $key, $value)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @param $connection
     * @param $key
     * @param $value
     *
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed|null|static
     */
    private function findByKeyValue($connection, $key, $value)
    {
        $model = $this->model();
        $model->setConnection($connection);
        $user = $model->where($key, $value)->first();
        if ($user) {
            return $user;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function find($identifier)
    {
        $connections = Loader::connections();
        if ($connections) {
            return $this->findByIdIn($connections, $identifier);
        }

        return $this->findById(Loader::defaultConnection(), $identifier);
    }

    /**
     * @param $connections
     * @param $identifier
     *
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed|null|static
     */
    private function findByIdIn($connections, $identifier)
    {
        foreach ($connections as $connection) {
            if ($user = $this->findById($connection, $identifier)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @param $connection
     * @param $identifier
     *
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed|null|static
     */
    private function findById($connection, $identifier)
    {
        $model = $this->model();
        $model->setConnection($connection);
        $user = $model->find($identifier);
        if (!$user) {
            return false;
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return 'eloquent';
    }

}