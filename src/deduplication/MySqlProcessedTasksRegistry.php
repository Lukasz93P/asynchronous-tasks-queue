<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication;


use Lukasz93P\tasksQueue\connection\Connection;
use Lukasz93P\tasksQueue\deduplication\exceptions\RegistrySavingFailed;
use Lukasz93P\tasksQueue\deduplication\exceptions\RegistryUnavailable;
use RuntimeException;

class MySqlProcessedTasksRegistry implements ProcessedTasksRegistry
{
    private const TABLE_NAME = 'processed_tasks_registry_lukasz93p';

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(string $taskId): void
    {
        $wasInserted = $this->connection->query('INSERT INTO ' . self::TABLE_NAME . "(task_id) VALUES('$taskId')");
        if ($wasInserted === false) {
            throw RegistrySavingFailed::fromTaskId($taskId);
        }
    }

    public function exists(string $taskId): bool
    {
        $queryResult = $this->connection->query('SELECT task_id FROM ' . self::TABLE_NAME . " WHERE task_id = '$taskId'");
        if ($queryResult === false) {
            throw RegistryUnavailable::reasonNotKnown();
        }

        return (bool)$queryResult->num_rows;
    }

    public function removeEntriesOlderThan(int $daysNumber): void
    {
        $this->connection->query('DELETE FROM ' . self::TABLE_NAME . " WHERE registered_at < (CURDATE() - INTERVAL $daysNumber DAY)");
    }

    public function initialize(): void
    {
        $result = $this->connection->query(
            'CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
                    task_id VARCHAR(55) NOT NULL,
                    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX(registered_at)
                    )'
        );

        if ($result === false) {
            throw new RuntimeException('Processed tasks registry table has not been created.');
        }
    }

}