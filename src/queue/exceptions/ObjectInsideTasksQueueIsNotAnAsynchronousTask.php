<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\queue\exceptions;


use Lukasz93P\tasksQueue\ProcessableAsynchronousTask;
use Throwable;

class ObjectInsideTasksQueueIsNotAnAsynchronousTask extends AsynchronousQueueException
{
    /**
     * @var object
     *
     */
    private $object;

    public static function fromObject($object): self
    {
        return new self($object, "Serialized object from tasks queue is not instance of " . ProcessableAsynchronousTask::class);
    }

    /**
     * ObjectInsideEventsQueueIsNotAnEvent constructor.
     * @param object $object
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    private function __construct($object, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->object = $object;
    }

    public function object()
    {
        return $this->object;
    }

}