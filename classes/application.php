<?php

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArgvInput;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        // @todo use database-group option!
        $this->definition->addOption(new InputOption('database-group', NULL, InputOption::VALUE_OPTIONAL, 'Kohana database group.', 'default'));
    }
}