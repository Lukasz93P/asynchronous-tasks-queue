<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue;


use Assert\Assertion;
use Assert\AssertionFailedException;
use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;

abstract class BaseTask implements PublishableAsynchronousTask, ProcessableAsynchronousTask
{
    /**
     * @var string
     * @Serializer\SerializedName("id")
     * @Serializer\Type("string")
     */
    private $id;

    /**
     * @var string
     * @Serializer\SerializedName("routingKey")
     * @Serializer\Type("string")
     */
    private $routingKey;

    /**
     * @var string
     * @Serializer\SerializedName("exchange")
     * @Serializer\Type("string")
     */
    private $exchange;

    /**
     * @var string
     * @Serializer\SerializedName("classIdentificationKey")
     * @Serializer\Type("string")
     */
    private $classIdentificationKey;

    protected function __construct(string $id, string $routingKey, string $exchange, string $classIdentificationKey)
    {
        $this->id = $id;
        $this->setRoutingKey($routingKey);
        $this->setExchange($exchange);
        $this->setClassIdentificationKey($classIdentificationKey);
    }

    private function setRoutingKey(string $routingKey): self
    {
        try {
            Assertion::notBlank($routingKey);
        } catch (AssertionFailedException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
        $this->routingKey = $routingKey;

        return $this;
    }

    private function setExchange(string $exchange): self
    {
        try {
            Assertion::notBlank($exchange);
        } catch (AssertionFailedException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
        $this->exchange = $exchange;

        return $this;
    }

    private function setClassIdentificationKey(string $classIdentificationKey): self
    {
        try {
            Assertion::notBlank($classIdentificationKey);
        } catch (AssertionFailedException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
        $this->classIdentificationKey = $classIdentificationKey;

        return $this;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function routingKey(): string
    {
        return $this->routingKey;
    }

    public function exchange(): string
    {
        return $this->exchange;
    }

    public function classIdentificationKey(): string
    {
        return $this->classIdentificationKey;
    }

}