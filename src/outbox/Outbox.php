<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\outbox;


use Lukasz93P\tasksQueue\outbox\exceptions\TasksAddingFailed;
use Lukasz93P\tasksQueue\PublishableAsynchronousTask;
use Lukasz93P\tasksQueue\queue\exceptions\EnqueuingFailed;
use Lukasz93P\tasksQueue\queue\Queue;
use RuntimeException;

interface Outbox
{
    /**
     * @throws RuntimeException
     */
    public function initialize(): void;

    /**
     * @param PublishableAsynchronousTask[] $tasks
     * @throws TasksAddingFailed
     */
    public function add(array $tasks): void;

    /**
     * @param Queue $queue
     * @throws EnqueuingFailed
     */
    public function publish(Queue $queue): void;
}