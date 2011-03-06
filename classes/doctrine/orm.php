<?php

/**
 *
 */
class Doctrine_ORM
{
    private static $doctrine_config;
    private $evm;
    private $em;

    /**
     *
     * @param <type> $doctrine_config
     */
    public static function set_config($doctrine_config)
    {
        self::$doctrine_config = $doctrine_config;
    }

    /**
     *
     * @param <type> $database_group 
     */
    public function __construct($database_group = 'default')
    {
        // if conig was not set by init.php, load it
        if (self::$doctrine_config === NULL)
        {
            self::$doctrine_config = Kohana::config('doctrine');
        }

        $config = new \Doctrine\ORM\Configuration();

        // Proxy Configuration
        $config->setProxyDir(self::$doctrine_config['proxy_dir']);
        $config->setProxyNamespace(self::$doctrine_config['proxy_namespace']);
        $config->setAutoGenerateProxyClasses((Kohana::$environment == Kohana::DEVELOPMENT));

        // Caching Configuration
        // @todo make this configurable
        $cache = new \Doctrine\Common\Cache\MemcacheCache();
        $memcache = new Memcache();
        $memcache->connect('memgear1.local', 11211, 300);
        $cache->setMemcache($memcache);
        $config->setMetadataCacheImpl($cache);

        // Mapping Configuration
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

        // Mappings between Kohaha database types and Doctrine database drivers
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

        // Get $database_group config
        $database_config = Arr::GET(Kohana::config('database'), $database_group, array());

        // Database configuration
        $connectionOptions = array(
            'driver' => $type_driver_mapping[$database_config['type']],
            'host' => $database_config['connection']['hostname'],
            'dbname' => $database_config['connection']['database'],
            'user' => $database_config['connection']['username'],
            'password' => $database_config['connection']['password'],
            'charset' => $database_config['charset'],
        );

        // Create Entity Manager
        $this->evm = new \Doctrine\Common\EventManager();
        $this->em  = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $this->evm);

        // Specify the charset with MySQL/PDO
        if ($type_driver_mapping[$database_config['type']] == 'pdo_mysql')
        {
            $this->em->getEventManager()->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit($database_config['charset'], 'utf8_unicode_ci'));
        }

        // Profiling
        if ($database_config['profiling'])
        {
            // @todo
        }
    }

    /**
     *
     * @return <type>
     */
    public function get_entity_manager()
    {
        return $this->em;
    }

    /**
     *
     * @return <type>
     */
    public function get_event_manager()
    {
        return $this->evm;
    }
}