<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\events\eventRecorder;


use Lukasz93P\tasksQueue\events\Event;

interface EventRecorder
{
    /**
     * @return Event[]
     */
    public function recordedEvents(): array;

    public function clearEvents(): void;
}