<?php

/**
 * configuration file for kohana-doctrine module
 *
 * you can define you own namespace for "entities" and "proxies"
 *
 * LICENSE: THE WORK (AS DEFINED BELOW) IS PROVIDED UNDER THE TERMS OF THIS
 * CREATIVE COMMONS PUBLIC LICENSE ("CCPL" OR "LICENSE"). THE WORK IS PROTECTED
 * BY COPYRIGHT AND/OR OTHER APPLICABLE LAW. ANY USE OF THE WORK OTHER THAN AS
 * AUTHORIZED UNDER THIS LICENSE OR COPYRIGHT LAW IS PROHIBITED.
 *
 * BY EXERCISING ANY RIGHTS TO THE WORK PROVIDED HERE, YOU ACCEPT AND AGREE TO
 * BE BOUND BY THE TERMS OF THIS LICENSE. TO THE EXTENT THIS LICENSE MAY BE
 * CONSIDERED TO BE A CONTRACT, THE LICENSOR GRANTS YOU THE RIGHTS CONTAINED HERE
 * IN CONSIDERATION OF YOUR ACCEPTANCE OF SUCH TERMS AND CONDITIONS.
 *
 * @category  module
 * @package   kohana-doctrine
 * @author    gimpe <gimpehub@intljaywalkers.com>
 * @copyright 2011 International Jaywalkers
 * @license   http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 * @link      http://github.com/gimpe/kohana-doctrine
 */
return array(
    // location of the "Doctrine" directory
    'doctrine_path' => MODPATH . 'kohana-doctrine/vendor/doctrine-orm/',
    // classloader config
    'entities_namespace' => 'Entities',
    'entities_path' => APPPATH . 'classes',
    'proxies_namespace' => 'Proxies',
    'proxies_path' => APPPATH . 'classes',
    // doctrine config
    'proxy_dir' => APPPATH . 'classes/Proxies',
    'proxy_namespace' => 'Proxies',
    'mappings_path' => APPPATH . 'mappings/yml',
    'mappings_driver' => 'yml',
    // mappings between Kohaha database types and Doctrine database drivers
    // @see http://kohanaframework.org/3.1/guide/database/config#connection-settings
    // @see http://www.doctrine-project.org/docs/dbal/2.0/en/reference/configuration.html#connection-details
    'type_driver_mapping' => array(
        'mysql' => 'pdo_mysql',
        'pdo' => 'pdo_mysql',
        //'N/A' => 'pdo_sqlite',
        //'N/A' => 'pdo_pgsql',
        //'N/A' => 'pdo_oci',
        //'N/A' => 'oci8',
    ),
);
