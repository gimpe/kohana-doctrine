<?php

/**
 * kohana-doctrine command line interface
 *
 * add an extra input option: --database-group
 * This option is used to select another Kohana database group
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

// your installation paths (needed to run CLI)
if (file_exists(__DIR__ . '/../../../../application/'))
{
    $system      = realpath(__DIR__ . '/../../../kohana/system/');
    $application = realpath(__DIR__ . '/../../../../application/');
}
else
{
    $system      = realpath(__DIR__ . '/../../../system/');
    $application = realpath(__DIR__ . '/../../../application/');
}
$modules = realpath(__DIR__ . '/../../');

if ($application === FALSE || $modules === FALSE || $system === FALSE)
{
    exit('please configure kohana-doctrine/bin/doctrine.php paths' . PHP_EOL
            . 'application: ' . $application . PHP_EOL
            . 'modules: '     . $modules     . PHP_EOL
            . 'system: '      . $system      . PHP_EOL);
}

// define constants (index.php)
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('EXT', '.php');
define('APPPATH', realpath($application) . DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules) . DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system) . DIRECTORY_SEPARATOR);

// include your Kohana application bootstrap
include $application . '/bootstrap.php';

// turn off caching
Kohana::$caching = FALSE;

// restore PHP handler for CLI display
restore_error_handler();
restore_exception_handler();

// use "default" if no "--database-group="
$database_group = 'default';

// hack to get --database-group and pass it to the Doctrine_ORM constructor
$argv2 = $argv;
foreach ($argv as $pos => $arg)
{
    if (strpos($arg, '--database-group') !== FALSE)
    {
        $parts = explode('=', $arg);
        $database_group = $parts[1];
        unset($argv2[$pos]);
    }
}
$input = new Symfony\Component\Console\Input\ArgvInput($argv2);
// end: hack to get --database-group and pass it to the Doctrine_ORM constructor

// create a Doctrine_ORM for one database group
$doctrine_orm = new Doctrine_ORM($database_group);

// add console helpers
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($doctrine_orm->get_entity_manager()->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($doctrine_orm->get_entity_manager())
));

// create and run Symfony Console application
$cli = new Symfony\Component\Console\Application('Kohana Doctrine Command Line Interface</info>'
        . PHP_EOL . '<comment>use --database-group to specifify another group from database.php (defaut: default)</comment>'
        . PHP_EOL . '<info>Doctrine', \Doctrine\ORM\Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);
$cli->run($input);
