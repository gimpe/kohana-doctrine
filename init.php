<?php

/**
 * @author gimpe
 * @link http://github.com/gimpe/kohana-doctrine
 * @license CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)
 */

// include kohana-doctrine config
$doctrine_config = Kohana::config('doctrine');

// include Doctrine ClassLoader.php
include $doctrine_config['doctrine_path'] . 'Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader(
        'Doctrine', $doctrine_config['doctrine_path']);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader(
        'Symfony', $doctrine_config['doctrine_path'] . '/Doctrine');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader(
        $doctrine_config['entities_namespace'], $doctrine_config['entities_path']);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader(
        $doctrine_config['proxies_namespace'], $doctrine_config['proxies_path']);
$classLoader->register();

// re-use already loaded Doctrine config
Doctrine_ORM::set_config($doctrine_config);