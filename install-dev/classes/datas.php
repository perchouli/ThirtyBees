<?php
/**
 * 2007-2016 PrestaShop
 *
 * Thirty Bees is an extension to the PrestaShop e-commerce software developed by PrestaShop SA
 * Copyright (C) 2017 Thirty Bees
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@thirtybees.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.thirtybees.com for more information.
 *
 *  @author    Thirty Bees <contact@thirtybees.com>
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2017 Thirty Bees
 *  @copyright 2007-2016 PrestaShop SA
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  PrestaShop is an internationally registered trademark & property of PrestaShop SA
 */

class Datas
{
    private static $instance = null;
    protected static $available_args = [
        'step' => [
            'name' => 'step',
            'default' => 'all',
            'validate' => 'isGenericName',
            'help' => 'all / database,fixtures,theme,modules,addons_modules',
        ],
        'language' => [
            'default' => 'en',
            'validate' => 'isLanguageIsoCode',
            'alias' => 'l',
            'help' => 'language iso code',
        ],
        'all_languages' => [
            'default' => '0',
            'validate' => 'isInt',
            'alias' => 'l',
            'help' => 'install all available languages',
        ],
        'timezone' => [
            'default' => 'Europe/Paris',
            'alias' => 't',
        ],
        'base_uri' => [
            'name' => 'base_uri',
            'validate' => 'isUrl',
            'default' => '/',
        ],
        'http_host' => [
            'name' => 'domain',
            'validate' => 'isGenericName',
            'default' => 'localhost',
        ],
        'database_server' => [
            'name' => 'db_server',
            'default' => 'localhost',
            'validate' => 'isGenericName',
            'alias' => 'h',
        ],
        'database_login' => [
            'name' => 'db_user',
            'alias' => 'u',
            'default' => 'root',
            'validate' => 'isGenericName',
        ],
        'database_password' => [
            'name' => 'db_password',
            'alias' => 'p',
            'default' => '',
        ],
        'database_name' => [
            'name' => 'db_name',
            'alias' => 'd',
            'default' => 'prestashop',
            'validate' => 'isGenericName',
        ],
        'database_clear' => [
            'name' => 'db_clear',
            'default' => '1',
            'validate' => 'isInt',
            'help' => 'Drop existing tables'
        ],
        'database_create' => [
            'name' => 'db_create',
            'default' => '0',
            'validate' => 'isInt',
            'help' => 'Create the database if not exist'
        ],
        'database_prefix' => [
            'name' => 'prefix',
            'default' => 'ps_',
            'validate' => 'isGenericName',
        ],
        'database_engine' => [
            'name' => 'engine',
            'validate' => 'isMySQLEngine',
            'default' => 'InnoDB',
            'help' => 'InnoDB/MyISAM',
        ],
        'shop_name' => [
            'name' => 'name',
            'validate' => 'isGenericName',
            'default' => 'PrestaShop',
        ],
        'shop_activity'    => [
            'name' => 'activity',
            'default' => 0,
            'validate' => 'isInt',
        ],
        'shop_country' => [
            'name' => 'country',
            'validate' => 'isLanguageIsoCode',
            'default' => 'fr',
        ],
        'admin_firstname' => [
            'name' => 'firstname',
            'validate' => 'isName',
            'default' => 'John',
        ],
        'admin_lastname'    => [
            'name' => 'lastname',
            'validate' => 'isName',
            'default' => 'Doe',
        ],
        'admin_password' => [
            'name' => 'password',
            'validate' => 'isPasswd',
            'default' => '0123456789',
        ],
        'admin_email' => [
            'name' => 'email',
            'validate' => 'isEmail',
            'default' => 'pub@prestashop.com'
        ],
        'show_license' => [
            'name' => 'license',
            'default' => 0,
            'help' => 'show PrestaShop license'
        ],
        'newsletter' => [
            'name' => 'newsletter',
            'default' => 1,
            'help' => 'get news from PrestaShop',
        ],
        'send_email' => [
            'name' => 'send_email',
            'default' => 1,
            'help' => 'send an email to the administrator after installation',
        ],
    ];

    protected $datas = [];

    public function __get($key)
    {
        if (isset($this->datas[$key])) {
            return $this->datas[$key];
        }

        return false;
    }

    public function __set($key, $value)
    {
        $this->datas[$key] = $value;
    }

    public static function getInstance()
    {
        if (Datas::$instance === null) {
            Datas::$instance = new Datas();
        }
        return Datas::$instance;
    }

    public static function getArgs()
    {
        return Datas::$available_args;
    }

    public function getAndCheckArgs($argv)
    {
        if (!$argv) {
            return false;
        }

        $args_ok = [];
        foreach ($argv as $arg) {
            if (!preg_match('/^--([^=\'"><|`]+)(?:=([^=><|`]+)|(?!license))/i', trim($arg), $res)) {
                continue;
            }

            if ($res[1] == 'license' && !isset($res[2])) {
                $res[2] = 1;
            } elseif (!isset($res[2])) {
                continue;
            }

            $args_ok[$res[1]] = $res[2];
        }

        $errors = [];
        foreach (Datas::getArgs() as $key => $row) {
            if (isset($row['name'])) {
                $name = $row['name'];
            } else {
                $name = $key;
            }
            if (!isset($args_ok[$name])) {
                if (!isset($row['default'])) {
                    $errors[] = 'Field '.$row['name'].' is empty';
                } else {
                    $this->$key = $row['default'];
                }
            } elseif (isset($row['validate']) && !call_user_func(['Validate', $row['validate']], $args_ok[$name])) {
                $errors[] = 'Field '.$key.' is not valid';
            } else {
                $this->$key = $args_ok[$name];
            }
        }

        return count($errors) ? $errors : true;
    }
}
