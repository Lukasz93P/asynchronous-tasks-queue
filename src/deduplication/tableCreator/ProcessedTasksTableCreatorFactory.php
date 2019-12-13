<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\tableCreator;


use InvalidArgumentException;
use Lukasz93P\tasksQueue\deduplication\MySqlProcessedTasksRegistry;

class ProcessedTasksTableCreatorFactory
{
    public const TYPE_MY_SQL = 'mysql';

    public static function make(string $type): ProcessedTasksTableCreator
    {
        $type = strtolower($type);
        switch ($type) {
            case self::TYPE_MY_SQL:
                return new MySqlProcessedTasksRegistry();
            default:
                throw new InvalidArgumentException('Unsupported processed tasks registry type.');
        }
    }

}