<?php

/**
* @author gimpe
* @license http://creativecommons.org/licenses/by-sa/3.0/
*/

// include kohana-doctrine config
$doctrine_config = Kohana::config('doctrine');

// include Donctrine's ClassLoader.php
include $doctrine_config['doctrine_path'] . 'Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $doctrine_config['doctrine_path']);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Symfony', $doctrine_config['doctrine_path'] . '/Doctrine');
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Entities', $doctrine_config['entities_path']);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', $doctrine_config['proxies_path']);
$classLoader->register();

// re-use already loaded Doctrine config
Doctrine_ORM::set_config($doctrine_config);