<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\exceptions;


use Throwable;

class RegistryUnavailable extends RegistryException
{
    public static function reasonNotKnown(): self
    {
        return new self('Registry unavailable.');
    }

    public static function fromReason(Throwable $reason): self
    {
        return new self("Registry unavailable reason: {$reason->getMessage()}", $reason->getCode(), $reason);
    }

    private function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}