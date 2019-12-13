<?php
declare(strict_types=1);

use Lukasz93P\tasksQueue\deduplication\tableCreator\command\CreateProcessedTasksRegistryTableCommand;
use Symfony\Component\Console\Application;

require_once '../vendor/autoload.php';

$consoleApplication = new Application();
$consoleApplication->add(new CreateProcessedTasksRegistryTableCommand());
$consoleApplication->run();