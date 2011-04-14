<?php

/**
 * kohana-doctrine initialization, executed by application bootstrap.php
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

// include kohana-doctrine config
$doctrine_config = Kohana::config('doctrine');

// include Doctrine ClassLoader.php
include $doctrine_config['doctrine_path'] . 'Doctrine/Common/ClassLoader.php';

// defines Doctrine namespace
$classLoader = new \Doctrine\Common\ClassLoader(
        'Doctrine', $doctrine_config['doctrine_path']);
$classLoader->register();

// defines Symfony namespace
$classLoader = new \Doctrine\Common\ClassLoader(
        'Symfony', $doctrine_config['doctrine_path'] . '/Doctrine');
$classLoader->register();

// defines your "entitites" namespace
$classLoader = new \Doctrine\Common\ClassLoader(
        $doctrine_config['entities_namespace'], $doctrine_config['entities_path']);
$classLoader->register();

// defines your "proxies" namespace
$classLoader = new \Doctrine\Common\ClassLoader(
        $doctrine_config['proxies_namespace'], $doctrine_config['proxies_path']);
$classLoader->register();

// re-use already loaded Doctrine config
Doctrine_ORM::set_config($doctrine_config);