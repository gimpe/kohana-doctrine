<?php

/**
 * creates a Doctrine EntityManager for a specific database group
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
 * @category   module
 * @package    kohana-doctrine
 * @author     gimpe <gimpehub@intljaywalkers.com>
 * @copyright  2011 International Jaywalkers
 * @license    http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 * @link       http://github.com/gimpe/kohana-doctrine
 */

use \Doctrine\ORM\Configuration;
use \Doctrine\ORM\EntityManager;
use \Doctrine\Common\EventManager;
use \Doctrine\Common\Cache\ArrayCache;
use \Doctrine\Common\Cache\MemcacheCache;
use \Doctrine\Common\Cache\ApcCache;
use \Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
use \Doctrine\ORM\Mapping\Driver\YamlDriver;
use \Doctrine\ORM\Mapping\Driver\XmlDriver;
use \Doctrine\ORM\Mapping\Driver\PHPDriver;

/**
 * creates a Doctrine EntityManager for a specific database group
 *
 * @category  module
 * @package   kohana-doctrine
 * @author    gimpe <gimpehub@intljaywalkers.com>
 * @copyright 2011 International Jaywalkers
 * @license   http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 * @link      http://github.com/gimpe/kohana-doctrine
*/
class Doctrine_ORM
{
    private static $doctrine_config;
    private static $database_config;
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
     * __constructor, you can specify which database group to use (default: 'default')
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

        $config = new Configuration();

        // proxy configuration
        $config->setProxyDir(self::$doctrine_config['proxy_dir']);
        $config->setProxyNamespace(self::$doctrine_config['proxy_namespace']);
        $config->setAutoGenerateProxyClasses((Kohana::$environment == Kohana::DEVELOPMENT));

        // caching configuration
        // @todo make this configurable; use kohana-cache module?
        $cache_implementation = new ArrayCache();
        // $cache_implementation = new MemcacheCache();
        // $cache_implementation = new ApcCache();
        $config->setMetadataCacheImpl($cache_implementation);

        // mappings/metadata driver configuration
        $driver_implementation = NULL;
        switch (self::$doctrine_config['mappings_driver'])
        {
            case 'php':
                $driver_implementation = new PHPDriver(array(self::$doctrine_config['mappings_path']));
                break;
            case 'xml':
                $driver_implementation = new XmlDriver(array(self::$doctrine_config['mappings_path']));
                break;
            default:
            case 'yaml':
                $driver_implementation = new YamlDriver(array(self::$doctrine_config['mappings_path']));
                break;
        }
        $config->setMetadataDriverImpl($driver_implementation);

        // load config if not defined
        if (self::$database_config === NULL)
        {
            self::$database_config = Kohana::config('database');
        }

        // get $database_group config
        $db_config = Arr::GET(self::$database_config, $database_group, array());

        // verify that the database group exists
        if (empty($db_config))
        {
            exit('database-group "' . $database_group . '" doesn\'t exists' . PHP_EOL);
        }

        // database configuration
        $connectionOptions = array(
            'driver' => self::$doctrine_config['type_driver_mapping'][$db_config['type']],
            'host' => $db_config['connection']['hostname'],
            'dbname' => $db_config['connection']['database'],
            'user' => $db_config['connection']['username'],
            'password' => $db_config['connection']['password'],
            'charset' => $db_config['charset'],
        );

        // create Entity Manager
        $this->evm = new EventManager();
        $this->em  = EntityManager::create($connectionOptions, $config, $this->evm);

        // specify the charset for MySQL/PDO
        if (self::$doctrine_config['type_driver_mapping'][$db_config['type']] == 'pdo_mysql')
        {
            $this->em->getEventManager()->addEventSubscriber(new MysqlSessionInit($db_config['charset'], 'utf8_unicode_ci'));
        }

        // @todo profiling
        //if ($db_config['profiling'])
        //{
        //}
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