<?php

/**
 * @author gimpe
 * @link http://github.com/gimpe/kohana-doctrine
 * @license CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)
 */

return array(
    // location of the "Doctrine" directory
    'doctrine_path' => SYSPATH . '../../../vendor/doctrine-orm/',
    // classloader config
    'entities_namespace' => 'Entities',
    'entities_path' => APPPATH . '/classes/',
    'proxies_namespace' => 'Proxies',
    'proxies_path' => APPPATH . '/classes/',
    // doctrine config
    'proxy_dir' => APPPATH . 'classes/Proxies',
    'proxy_namespace' => 'Proxies',
);