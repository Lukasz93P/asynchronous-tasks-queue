<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\outbox;


use Lukasz93P\objectSerializer\ObjectSerializerFactory;
use Lukasz93P\tasksQueue\connection\ConnectionFactory;
use Lukasz93P\tasksQueue\deduplication\ProcessedTasksRegistryFactory;

class OutboxFactory
{
    public static function create(array $tasksIdentificationKeysToClassNamesMapping, string $type = ProcessedTasksRegistryFactory::TYPE_MY_SQL): Outbox
    {
        return new MySqlOutbox(ConnectionFactory::create($type), ObjectSerializerFactory::create($tasksIdentificationKeysToClassNamesMapping));
    }

}