<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Config;

use Config;

/**
 * Class Loader
 *
 * @package Restricted\Authchain\Config
 */
class Loader
{

    /**
     * Get database connections for native provider
     *
     * @return array|bool
     */
    public static function connections()
    {
        return isset(self::defaults()['native']['connections']) ? self::defaults()['native']['connections'] : false;
    }

    /**
     * Get default database connection
     *
     * @return string
     */
    public static function defaultConnection()
    {
        return Config::get('database::default');
    }

    /**
     * Get domain configuration
     *
     * @public
     * @static
     *
     * @param string $domain
     *
     * @return array
     */
    public static function domain($domain)
    {
        return self::hasDomain($domain) ? self::domains()[$domain] : array();
    }

    /**
     * Configuraion for domain is exists or not
     *
     * @public
     * @static
     *
     * @param string $domain
     *
     * @return bool
     */
    public static function hasDomain($domain)
    {
        return isset(self::domains()[$domain]);
    }

    /**
     * Get domains configuration
     *
     * @public
     * @static
     *
     * @return array
     */
    public static function domains()
    {
        return Config::get('authchain::domains', array());
    }

    /**
     * Get ip address authentication config
     *
     * @return array
     */
    public static function ip()
    {
        return isset(self::defaults()['ip']) ? self::defaults()['ip'] : false;
    }

    /**
     * Get configuration defaults
     *
     * @public
     * @static
     * @return mixed
     */
    public static function defaults()
    {
        return Config::get('authchain::defaults', array());
    }

    /**
     * Get native provider name
     *
     * @public
     * @static
     * @return string
     */
    public static function native()
    {
        return isset(self::defaults()['native']['provider']) ? self::defaults()['native']['provider'] : 'eloquent';
    }

    /**
     * Get password field from Config. Fallback to default 'password'.
     *
     * @public
     * @static
     * @return string
     */
    public static function password()
    {
        return Config::get('auth.password', 'password');
    }

    /**
     * Get providers from configuration
     *
     * @public
     * @static
     * @return array
     */
    public static function providers()
    {
        return Config::get('authchain::providers', array());
    }

    /**
     * Get user model class name
     *
     * @public
     * @static
     * @return string
     */
    public static function user()
    {
        return Config::get('auth.model', 'User');
    }

    /**
     * Get username field from Config. Fallback to default 'username'.
     *
     * @public
     * @static
     * @return string Username database field
     */
    public static function username()
    {
        return Config::get('auth.username', 'username');
    }


} 