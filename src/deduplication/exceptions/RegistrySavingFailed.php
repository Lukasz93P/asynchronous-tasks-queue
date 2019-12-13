<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\exceptions;


use Throwable;

class RegistrySavingFailed extends RegistryException
{
    public static function fromTaskId(string $taskId): self
    {
        return new self("Saving tasks with $taskId failed");
    }

    public static function fromTaskIdAndReason(string $taskId, Throwable $reason): self
    {
        return new self("Saving tasks with $taskId failed. Reason: {$reason->getMessage()}", $reason->getCode(), $reason);
    }

    private function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}