<?php

//\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);

// @todo use database-group option!
$doctrine_orm = new Doctrine_ORM('default');

// Add Console Helpers
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($doctrine_orm->get_entity_manager()->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($doctrine_orm->get_entity_manager())
));

$cli = new Application('Doctrine Command Line Interface', \Doctrine\ORM\Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);
$cli->run();