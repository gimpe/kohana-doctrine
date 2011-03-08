<?php

/**
 * Doctrine_ORM
 *
 * @author gimpe
 * @link http://github.com/gimpe/kohana-doctrine
 * @license CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)
 */
class Doctrine_ORM
{
    private static $doctrine_config;
    private $evm;
    private $em;

    /**
     * set Kohana database configuration
     *
     * @param array $doctrine_config
     */
    public static function set_config($doctrine_config)
    {
        self::$doctrine_config = $doctrine_config;
    }

    /**
     * constructor, you can specify which database group to use (default: 'default')
     *
     * @param string $database_group
     */
    public function __construct($database_group = 'default')
    {
        // if config was not set by init.php, load it
        if (self::$doctrine_config === NULL)
        {
            self::$doctrine_config = Kohana::config('doctrine');
        }

        $config = new \Doctrine\ORM\Configuration();

        // proxy configuration
        $config->setProxyDir(self::$doctrine_config['proxy_dir']);
        $config->setProxyNamespace(self::$doctrine_config['proxy_namespace']);
        $config->setAutoGenerateProxyClasses((Kohana::$environment == Kohana::DEVELOPMENT));

        // caching configuration
        // @todo make this configurable; use cache module?
        if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
            $cache = new \Doctrine\Common\Cache\ArrayCache();
        }
        else if (FALSE)
        {
            $cache = new \Doctrine\Common\Cache\MemcacheCache();
        }
        else
        {
            $cache = new \Doctrine\Common\Cache\ApcCache();
        }

        $config->setMetadataCacheImpl($cache);

        // mapping configuration
        // @todo make this configurable
#        if (PHP_SAPI == 'cli')
#        {
            $driverImpl = new \Doctrine\ORM\Mapping\Driver\YamlDriver(array(APPPATH . '../mappings/yml'));
            $config->setMetadataDriverImpl($driverImpl);
#        }
#        else
#        {
#            $driverImpl = $config->newDefaultAnnotationDriver(array(APPPATH . 'Entities'));
#            $config->setMetadataDriverImpl($driverImpl);
#        }

        // mappings between Kohaha database types and Doctrine database drivers
        // @see http://kohanaframework.org/3.1/guide/database/config#connection-settings
        // @see http://www.doctrine-project.org/docs/dbal/2.0/en/reference/configuration.html#connection-details
        $type_driver_mapping = array(
            'mysql' => 'pdo_mysql',
            'pdo' => 'pdo_mysql',
            //'' => 'pdo_sqlite',
            //'' => 'pdo_pgsql',
            //'' => 'pdo_oci',
            //'' => 'oci8',
        );

        // get $database_group config
        $database_config = Arr::GET(Kohana::config('database'), $database_group, array());

        // database configuration
        $connectionOptions = array(
            'driver' => $type_driver_mapping[$database_config['type']],
            'host' => $database_config['connection']['hostname'],
            'dbname' => $database_config['connection']['database'],
            'user' => $database_config['connection']['username'],
            'password' => $database_config['connection']['password'],
            'charset' => $database_config['charset'],
        );

        // create Entity Manager
        $this->evm = new \Doctrine\Common\EventManager();
        $this->em  = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $this->evm);

        // specify the charset for MySQL/PDO
        if ($type_driver_mapping[$database_config['type']] == 'pdo_mysql')
        {
            $this->em->getEventManager()->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit($database_config['charset'], 'utf8_unicode_ci'));
        }

        // profiling
        if ($database_config['profiling'])
        {
            // @todo
        }
    }

    /**
     * get EntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function get_entity_manager()
    {
        return $this->em;
    }

    /**
     * get EventManager
     *
     * @return \Doctrine\Common\EventManager
     */
    public function get_event_manager()
    {
        return $this->evm;
    }
}