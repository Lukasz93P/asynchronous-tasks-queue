<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\tableCreator;


use Exception;

interface ProcessedTasksTableCreator
{
    /**
     * @throws Exception
     */
    public function createTable(): void;
}