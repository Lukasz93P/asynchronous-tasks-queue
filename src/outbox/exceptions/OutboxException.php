<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\outbox\exceptions;


use RuntimeException;
use Throwable;

abstract class OutboxException extends RuntimeException
{
    protected function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}