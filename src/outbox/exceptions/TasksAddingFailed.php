<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\outbox\exceptions;


use Throwable;

class TasksAddingFailed extends OutboxException
{
    private const MESSAGE = 'Adding tasks to outbox failed';

    public static function reasonNotKnown(): self
    {
        return new self(self::MESSAGE);
    }

    public static function fromReason(Throwable $reason): self
    {
        return new self(self::MESSAGE, $reason->getCode(), $reason);
    }

}