<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\queue;


use Lukasz93P\AsyncMessageChannel\AsynchronousMessageChannelFactory;
use Lukasz93P\objectSerializer\ObjectSerializerFactory;
use Lukasz93P\tasksQueue\deduplication\ProcessedTasksRegistry;
use Lukasz93P\tasksQueue\deduplication\ProcessedTasksRegistryFactory;
use Lukasz93P\tasksQueue\deduplication\tableCreator\ProcessedTasksTableCreatorFactory;
use Lukasz93P\tasksQueue\serializableMessageConverter\SerializableMessageConverterBasedOnObjectSerializer;
use Psr\Log\LoggerInterface;

final class QueueFactory
{
    public static function publisher(
        LoggerInterface $logger,
        array $tasksIdentificationKeysToClassNamesMapping
    ): Queue {
        return new AsynchronousMessageQueue(
            AsynchronousMessageChannelFactory::withLogger($logger),
            SerializableMessageConverterBasedOnObjectSerializer::withObjectSerializer(
                ObjectSerializerFactory::create($tasksIdentificationKeysToClassNamesMapping)
            ),
            new class implements ProcessedTasksRegistry {
                public function save(string $taskId): void
                {
                    return;
                }

                public function exists(string $taskId): bool
                {
                    return false;
                }

            }
        );
    }

    public static function publisherAndSubscriber(
        LoggerInterface $logger,
        array $tasksIdentificationKeysToClassNamesMapping,
        string $processedTasksRegistryType = ProcessedTasksTableCreatorFactory::TYPE_MY_SQL
    ): AsynchronousQueue {
        return new AsynchronousMessageQueue(
            AsynchronousMessageChannelFactory::withLogger($logger),
            SerializableMessageConverterBasedOnObjectSerializer::withObjectSerializer(
                ObjectSerializerFactory::create($tasksIdentificationKeysToClassNamesMapping)
            ),
            ProcessedTasksRegistryFactory::fromType($processedTasksRegistryType)
        );
    }

}