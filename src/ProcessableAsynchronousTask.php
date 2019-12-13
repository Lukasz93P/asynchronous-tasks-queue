<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue;


interface ProcessableAsynchronousTask
{
    public function id(): string;
}