<?php

/**
 * @author gimpe
 * @link http://github.com/gimpe/kohana-doctrine
 * @license CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)
 */

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