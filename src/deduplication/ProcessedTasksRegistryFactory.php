<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication;


use InvalidArgumentException;
use Lukasz93P\tasksQueue\connection\ConnectionFactory;

class ProcessedTasksRegistryFactory
{
    public const TYPE_MY_SQL = 'mysql';

    public static function fromType(string $processedTaskRegistryType): ProcessedTasksRegistry
    {
        switch ($processedTaskRegistryType) {
            case self::TYPE_MY_SQL:
                return new MySqlProcessedTasksRegistry(ConnectionFactory::create($processedTaskRegistryType));
            default:
                throw new InvalidArgumentException("$processedTaskRegistryType not supported.");
        }
    }

}