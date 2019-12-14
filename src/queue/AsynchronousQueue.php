<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\queue;


use Throwable;

interface AsynchronousQueue extends Queue
{
    /**
     * @param string $queueKey
     * @param int $timeoutInSeconds
     * @throws Throwable
     */
    public function startTasksProcessingLoop(string $queueKey, int $timeoutInSeconds = 0): void;
}