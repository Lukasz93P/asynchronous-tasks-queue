<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication;


use InvalidArgumentException;
use Lukasz93P\tasksQueue\deduplication\tableCreator\ProcessedTasksTableCreatorFactory;

class ProcessedTasksRegistryFactory
{
    public static function fromType(string $processedTaskRegistryType): ProcessedTasksRegistry
    {
        switch ($processedTaskRegistryType) {
            case ProcessedTasksTableCreatorFactory::TYPE_MY_SQL:
                return new MySqlProcessedTasksRegistry();
            default:
                throw new InvalidArgumentException("$processedTaskRegistryType not supported.");
        }
    }
}