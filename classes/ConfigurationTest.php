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
 * @author    Thirty Bees <contact@thirtybees.com>
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2017 Thirty Bees
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  PrestaShop is an internationally registered trademark & property of PrestaShop SA
 */

/**
 * Class ConfigurationTestCore
 *
 * @since 1.0.0
 */
class ConfigurationTestCore
{
    public static $test_files = [
        '/cache/smarty/compile/index.php',
        '/classes/log/index.php',
        '/classes/cache/index.php',
        '/config/index.php',
        '/tools/tar/Archive_Tar.php',
        '/tools/pear/PEAR.php',
        '/controllers/admin/AdminLoginController.php',
        '/css/index.php',
        '/download/index.php',
        '/img/404.gif',
        '/js/tools.js',
        '/js/jquery/plugins/fancybox/jquery.fancybox.js',
        '/localization/fr.xml',
        '/mails/index.php',
        '/modules/index.php',
        '/override/controllers/front/index.php',
        '/pdf/order-return.tpl',
        '/themes/community-theme-default/css/global.css',
        '/translations/export/index.php',
        '/webservice/dispatcher.php',
        '/index.php',
    ];

    /**
     * getDefaultTests return an array of tests to executes.
     * key are method name, value are parameters (false for no parameter)
     * all path are _PS_ROOT_DIR_ related
     *
     * @return array
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function getDefaultTests()
    {
        $tests = [
            'upload'                    => false,
            'cache_dir'                 => 'cache',
            'log_dir'                   => 'log',
            'img_dir'                   => 'img',
            'module_dir'                => 'modules',
            'theme_lang_dir'            => 'themes/'._THEME_NAME_.'/lang/',
            'theme_pdf_lang_dir'        => 'themes/'._THEME_NAME_.'/pdf/lang/',
            'theme_cache_dir'           => 'themes/'._THEME_NAME_.'/cache/',
            'translations_dir'          => 'translations',
            'customizable_products_dir' => 'upload',
            'virtual_products_dir'      => 'download',
        ];

        if (!defined('_PS_HOST_MODE_')) {
            $tests = array_merge(
                $tests, [
                    'system'        => [
                        'fopen', 'fclose', 'fread', 'fwrite',
                        'rename', 'file_exists', 'unlink', 'rmdir', 'mkdir',
                        'getcwd', 'chdir', 'chmod',
                    ],
                    'phpversion'    => false,
                    'gd'            => false,
                    'mysql_support' => false,
                    'config_dir'    => 'config',
                    'files'         => false,
                    'mails_dir'     => 'mails',
                ]
            );
        }

        return $tests;
    }

    /**
     * getDefaultTestsOp return an array of tests to executes.
     * key are method name, value are parameters (false for no parameter)
     *
     * @return array
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function getDefaultTestsOp()
    {
        return [
            'new_phpversion'   => false,
            'fopen'            => false,
            'register_globals' => false,
            'gz'               => false,
            'mcrypt'           => false,
            'mbstring'         => false,
            'magicquotes'      => false,
            'dom'              => false,
            'pdo_mysql'        => false,
        ];
    }

    /**
     * run all test defined in $tests
     *
     * @param array $tests
     *
     * @return array results of tests
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function check($tests)
    {
        $res = [];
        foreach ($tests as $key => $test) {
            $res[$key] = ConfigurationTest::run($key, $test);
        }

        return $res;
    }

    /**
     * @param     $ptr
     * @param int $arg
     *
     * @return string
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function run($ptr, $arg = 0)
    {
        if (call_user_func(['ConfigurationTest', 'test_'.$ptr], $arg)) {
            return 'ok';
        }

        return 'fail';
    }

    /**
     * @return mixed
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_phpversion()
    {
        return version_compare(substr(phpversion(), 0, 5), '5.2.0', '>=');
    }

    /**
     * @return mixed
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_new_phpversion()
    {
        return version_compare(substr(phpversion(), 0, 5), '5.4.0', '>=');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_mysql_support()
    {
        return extension_loaded('mysql') || extension_loaded('mysqli') || extension_loaded('pdo_mysql');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_pdo_mysql()
    {
        return extension_loaded('pdo_mysql');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_magicquotes()
    {
        return !get_magic_quotes_gpc();
    }

    /**
     * @return string
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_upload()
    {
        return ini_get('file_uploads');
    }

    /**
     * @return string
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_fopen()
    {
        return ini_get('allow_url_fopen');
    }

    /**
     * @param $funcs
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_system($funcs)
    {
        foreach ($funcs as $func) {
            if (!function_exists($func)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_gd()
    {
        return function_exists('imagecreatetruecolor');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_register_globals()
    {
        return !ini_get('register_globals');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_gz()
    {
        if (function_exists('gzencode')) {
            return @gzencode('dd') !== false;
        }

        return false;
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_config_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param      $relativeDir
     * @param bool $recursive
     * @param null $fullReport
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_dir($relativeDir, $recursive = false, &$fullReport = null)
    {
        $dir = rtrim(_PS_ROOT_DIR_, '\\/').DIRECTORY_SEPARATOR.trim($relativeDir, '\\/');
        if (!file_exists($dir) || !$dh = @opendir($dir)) {
            $fullReport = sprintf('Directory %s does not exist or is not writable', $dir); // sprintf for future translation
            return false;
        }
        $dummy = rtrim($dir, '\\/').DIRECTORY_SEPARATOR.uniqid();
        if (@file_put_contents($dummy, 'test')) {
            @unlink($dummy);
            if (!$recursive) {
                closedir($dh);

                return true;
            }
        } elseif (!is_writable($dir)) {
            $fullReport = sprintf('Directory %s is not writable', $dir); // sprintf for future translation
            return false;
        }

        if ($recursive) {
            while (($file = readdir($dh)) !== false) {
                if (is_dir($dir.DIRECTORY_SEPARATOR.$file) && $file != '.' && $file != '..' && $file != '.svn') {
                    if (!ConfigurationTest::test_dir($relativeDir.DIRECTORY_SEPARATOR.$file, $recursive, $fullReport)) {
                        return false;
                    }
                }
            }
        }

        closedir($dh);

        return true;
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_sitemap($dir)
    {
        return ConfigurationTest::test_file($dir);
    }

    /**
     * @param $fileRelative
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_file($fileRelative)
    {
        $file = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.$fileRelative;

        return (file_exists($file) && is_writable($file));
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_root_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_log_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_admin_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_img_dir($dir)
    {
        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_module_dir($dir)
    {
        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_cache_dir($dir)
    {
        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_tools_v2_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_cache_v2_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_download_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_mails_dir($dir)
    {
        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_translations_dir($dir)
    {
        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_theme_lang_dir($dir)
    {
        $absoluteDir = rtrim(_PS_ROOT_DIR_, '\\/').DIRECTORY_SEPARATOR.trim($dir, '\\/');
        if (!file_exists($absoluteDir)) {
            return true;
        }

        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_theme_pdf_lang_dir($dir)
    {
        $absoluteDir = rtrim(_PS_ROOT_DIR_, '\\/').DIRECTORY_SEPARATOR.trim($dir, '\\/');
        if (!file_exists($absoluteDir)) {
            return true;
        }

        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_theme_cache_dir($dir)
    {
        $absoluteDir = rtrim(_PS_ROOT_DIR_, '\\/').DIRECTORY_SEPARATOR.trim($dir, '\\/');
        if (!file_exists($absoluteDir)) {
            return true;
        }

        return ConfigurationTest::test_dir($dir, true);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_customizable_products_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @param $dir
     *
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_virtual_products_dir($dir)
    {
        return ConfigurationTest::test_dir($dir);
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_mbstring()
    {
        return function_exists('mb_strtolower');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_mcrypt()
    {
        return function_exists('mcrypt_encrypt');
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_sessions()
    {
        if (!$path = @ini_get('session.save_path')) {
            return true;
        }

        return is_writable($path);
    }

    /**
     * @return bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_dom()
    {
        return extension_loaded('Dom');
    }

    /**
     * @param bool $full
     *
     * @return array|bool
     *
     * @since   1.0.0
     * @version 1.0.0 Initial version
     */
    public static function test_files($full = false)
    {
        $return = [];
        foreach (ConfigurationTest::$test_files as $file) {
            if (!file_exists(rtrim(_PS_ROOT_DIR_, DIRECTORY_SEPARATOR).str_replace('/', DIRECTORY_SEPARATOR, $file))) {
                if ($full) {
                    array_push($return, $file);
                } else {
                    return false;
                }
            }
        }

        if ($full) {
            return $return;
        }

        return true;
    }
}
