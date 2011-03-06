<?php

/**
* @author gimpe
* @license http://creativecommons.org/licenses/by-sa/3.0/
*/

// @todo use database-group option from CLI!
$doctrine_orm = new Doctrine_ORM('default');

// add console helpers
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($doctrine_orm->get_entity_manager()->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($doctrine_orm->get_entity_manager())
));

//\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
$cli = new Application('Doctrine Command Line Interface', \Doctrine\ORM\Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);
$cli->run();