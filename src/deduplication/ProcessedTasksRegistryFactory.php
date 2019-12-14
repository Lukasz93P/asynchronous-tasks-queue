<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication;


use InvalidArgumentException;

class ProcessedTasksRegistryFactory
{
    public const TYPE_MY_SQL = 'mysql';

    public static function fromType(string $processedTaskRegistryType): ProcessedTasksRegistry
    {
        switch ($processedTaskRegistryType) {
            case self::TYPE_MY_SQL:
                return new MySqlProcessedTasksRegistry();
            default:
                throw new InvalidArgumentException("$processedTaskRegistryType not supported.");
        }
    }
}