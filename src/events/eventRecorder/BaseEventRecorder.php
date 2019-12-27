<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\events\eventRecorder;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Lukasz93P\tasksQueue\events\Event;

abstract class BaseEventRecorder implements EventRecorder
{
    /**
     * @var Collection|Event[]
     */
    private $recordedEvents;

    protected function __construct()
    {
        $this->recordedEvents = new ArrayCollection();
    }

    public function recordedEvents(): array
    {
        return $this->recordedEvents->toArray();
    }

    public function clearEvents(): void
    {
        $this->recordedEvents->clear();
    }

    protected function record(Event $event): void
    {
        if ($this->recordedEvents->contains($event)) {
            return;
        }
        $this->recordedEvents->add($event);
    }

}