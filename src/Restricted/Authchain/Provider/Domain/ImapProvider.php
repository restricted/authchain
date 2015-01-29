<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013-2015
 *
 **/

namespace Restricted\Authchain\Provider\Domain;

use Log;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Provider\Provider;
use Restricted\Authchain\Provider\ProviderInterface;

/**
 * Class ImapProvider
 *
 * @package Restricted\Authchain\Provider\Domain
 */
class ImapProvider extends Provider implements ProviderInterface
{
    protected $connection = false;

    /**
     * @inheritdoc
     */
    public function authenticate()
    {
        if (!extension_loaded('imap')) {
            throw new \Exception("Cannot use IMAP provider without imap module.", 1);
        }

        $this->config = Loader::domain($this->domain);

        foreach ($this->config['hosts'] as $name => $address) {
            try {
                $this->connection = \imap_open("{" . $address . "/novalidate-cert}", $this->username, $this->password, null, 1);
                if ($this->connection) {
                    break;
                }
            } catch (\Exception $e) {
                Log::warning(' [IMAP] Cannot connect to ' . $name . ': ' . $e->getMessage());
            }
        }
        if (!$this->connection) {
            return false;
        }

        if ($user = $this->resolver->native()->findBy($this->config['mappings'][Loader::username()], $this->username)) {
            return $user;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return 'imap';
    }

}