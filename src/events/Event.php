<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\events;


use Assert\Assertion;
use Assert\AssertionFailedException;
use Carbon\Carbon;
use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;
use Lukasz93P\tasksQueue\BaseTask;

abstract class Event extends BaseTask
{
    /**
     * @var string
     * @Serializer\SerializedName("aggregateId")
     * @Serializer\Type("string")
     */
    private $aggregateId;

    /**
     * @var string
     * @Serializer\SerializedName("occurredAt")
     * @Serializer\Type("string")
     */
    private $occurredAt;

    protected function __construct(EventId $id, string $routingKey, string $exchange, string $classIdentificationKey, string $aggregateId)
    {
        parent::__construct($id->toString(), $routingKey, $exchange, $classIdentificationKey);
        $this->setAggregateId($aggregateId);
        $this->occurredAt = Carbon::now()->toDateTimeString();
    }

    private function setAggregateId(string $aggregateId): self
    {
        try {
            Assertion::notBlank($aggregateId);
        } catch (AssertionFailedException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
        $this->aggregateId = $aggregateId;

        return $this;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredAt(): string
    {
        return $this->occurredAt;
    }

}