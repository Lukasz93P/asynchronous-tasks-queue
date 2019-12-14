<?php
declare(strict_types=1);

use Lukasz93P\tasksQueue\deduplication\commands\CreateProcessedTasksRegistryTableCommand;
use Lukasz93P\tasksQueue\deduplication\commands\RemoveOldEntriesFromProcessedTasksRegistryCommand;
use Symfony\Component\Console\Application;

require_once '../../autoload.php';

$consoleApplication = new Application();
$consoleApplication->add(new CreateProcessedTasksRegistryTableCommand());
$consoleApplication->add(new RemoveOldEntriesFromProcessedTasksRegistryCommand());
$consoleApplication->run();