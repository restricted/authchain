<?php

/**
 * This file is part of authchain, Laravel 4 chain authentication provider
 *
 * @author    Alexey Dementyev <alexey.dementyev@gmail.com>
 * @copyright Alexey Dementyev (c) 2013
 *
 **/

namespace Restricted\Authchain\Command;

use Illuminate\Console\Command;
use Restricted\Authchain\Config\Loader;
use Restricted\Authchain\Mapping\LdapMapping;
use Restricted\Authchain\Provider\Domain\Ldap\Connection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class LdapImportCommand
 *
 * @package Restricted\Authchain\Command
 */
class LdapImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'authchain:ldap:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from LDAP to database.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('domain', InputArgument::REQUIRED, 'Domain name to import from.'),
        );
    }

    protected function getOptions()
    {
        return array(
            array('username', 'u', InputOption::VALUE_OPTIONAL, 'Administrator username.'),
            array('password', 'p', InputOption::VALUE_OPTIONAL, 'Administrator password.'),
        );
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $domain   = $this->argument('domain');
        $username = $this->option('username');
        $password = $this->option('password');
        if (!$username) {
            $username = $this->ask('<info>Administrator username for <error>' . $domain . '</error>:  </info>');
        }
        if (!strstr($username, '@')) {
            $username .= '@' . $domain;
        }
        if (!$password) {
            $password = $this->secret('<info>Password:  </info>');
        }

        if (!Loader::hasDomain($domain)) {
            $this->error('Domain ' . $domain . ' not found in configuration.');
            exit(1);
        }

        $config = Loader::domain($domain);

        $ldap = new Connection();
        $ldap->connect($config['hosts']);
        if (!$ldap->bind($username, $password)) {
            $this->error('Bind to ' . $domain . ' with user ' . $username . ' failed.');
            exit(1);
        }

        $entries = $ldap->search($config['baseDN'], $config['mappings'], '(&(objectClass=user)(objectCategory=person))');

        if (!$entries) {
            $this->error('Users not found.');
            exit(1);
        }
        $ldapMapping = new LdapMapping($config['mappings']);
        $class       = '\\' . ltrim(Loader::user(), '\\');

        $usernameField = strtolower($config['mappings']['fields'][Loader::username()]);
        foreach ($entries as $entry) {
            if (!is_array($entry)) {
                continue;
            }

            if (!isset($entry[$usernameField])) {
                continue;
            }
            $model = new $class;
            $user  = $model->where(Loader::username(), $entry[$usernameField][0])->first();
            if ($user) {
                $model = $user;
                $this->info('Updating ' . $entry[$usernameField][0]);
            } else {
                $this->info('Adding ' . $entry[$usernameField][0]);
            }
            $ldapMapping->map($entry, $model);
        }

    }

}