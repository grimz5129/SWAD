<?php
/**
 * CLI_CONFIG.php
 * Command line interface config file for doctrine
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

/** @var Container $container */
$container = require_once './bootstrap.php';

return ConsoleRunner::createHelperSet($container[EntityManager::class]);

