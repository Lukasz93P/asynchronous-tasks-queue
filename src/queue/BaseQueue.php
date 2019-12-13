<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\queue;


use Lukasz93P\AsyncMessageChannel\exceptions\MessageConstantlyUnprocessable;
use Lukasz93P\AsyncMessageChannel\exceptions\MessageTemporaryUnprocessable;
use Lukasz93P\tasksQueue\deduplication\exceptions\RegistryException;
use Lukasz93P\tasksQueue\deduplication\ProcessedTasksRegistry;
use Lukasz93P\tasksQueue\ProcessableAsynchronousTask;
use Lukasz93P\tasksQueue\queue\exceptions\TaskConstantlyUnprocessable;
use Lukasz93P\tasksQueue\TaskHandler;

abstract class BaseQueue implements Queue
{
    /**
     * @var TaskHandler[]
     */
    private $registeredHandlers = [];

    /**
     * @var ProcessedTasksRegistry
     */
    private $processedTasksRegistry;

    public function __construct(ProcessedTasksRegistry $processedTasksRegistry)
    {
        $this->processedTasksRegistry = $processedTasksRegistry;
    }

    public function register(TaskHandler $taskHandler, string $taskClassName): Queue
    {
        $this->registeredHandlers[$taskClassName] = $taskHandler;

        return $this;
    }

    protected function handleTask(ProcessableAsynchronousTask $task): void
    {
        try {
            if ($this->processedTasksRegistry->exists($task->id())) {
                return;
            }
            $handler = $this->findHandlerFor($task);
            if (!$handler) {
                return;
            }
            $handler->handle($task);
            $this->processedTasksRegistry->save($task->id());
        } catch (TaskConstantlyUnprocessable $taskConstantlyUnprocessable) {
            throw MessageConstantlyUnprocessable::fromReason($taskConstantlyUnprocessable);
        } catch (RegistryException $registryException) {
            throw MessageTemporaryUnprocessable::fromReason($registryException);
        }
    }

    private function findHandlerFor(ProcessableAsynchronousTask $task): ?TaskHandler
    {
        return $this->registeredHandlers[get_class($task)] ?? null;
    }

}