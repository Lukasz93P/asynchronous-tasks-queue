<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication;


use Lukasz93P\tasksQueue\deduplication\exceptions\RegistrySavingFailed;
use Lukasz93P\tasksQueue\deduplication\exceptions\RegistryUnavailable;
use mysqli;
use RuntimeException;

class MySqlProcessedTasksRegistry implements ProcessedTasksRegistry
{
    /**
     * @var mysqli
     */
    private $mySqlConnection;

    public function __construct()
    {
        $this->mySqlConnection = new mysqli(
            getenv('TASKS_DEDUPLICATION_DATABASE_HOST') ?: 'localhost',
            getenv('TASKS_DEDUPLICATION_DATABASE_USER') ?: 'root',
            getenv('TASKS_DEDUPLICATION_DATABASE_PASSWORD') ?: '',
            getenv('TASKS_DEDUPLICATION_DATABASE_NAME') ?: 'code_quality_pacage_lifecycle',
            (int)(getenv('TASKS_DEDUPLICATION_DATABASE_PORT') ?: 3306),
        );
    }

    public function save(string $taskId): void
    {
        $wasInserted = $this->mySqlConnection->query("INSERT INTO processed_tasks_registry(task_id) VALUES('$taskId')");
        if ($wasInserted === false) {
            throw RegistrySavingFailed::fromTaskId($taskId);
        }
    }

    public function exists(string $taskId): bool
    {
        $queryResult = $this->mySqlConnection->query("SELECT task_id FROM processed_tasks_registry WHERE task_id = '$taskId'");
        if ($queryResult === false) {
            throw RegistryUnavailable::reasonNotKnown();
        }

        return (bool)$queryResult->num_rows;
    }

    public function removeEntriesOlderThan(int $daysNumber): void
    {
        $this->mySqlConnection->query("DELETE FROM processed_tasks_registry WHERE registered_at < (CURDATE() - INTERVAL $daysNumber DAY)");
    }

    public function initialize(): void
    {
        $result = $this->mySqlConnection->query(
            'CREATE TABLE IF NOT EXISTS processed_tasks_registry (
                    task_id VARCHAR(55) NOT NULL UNIQUE,
                    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX(registered_at)
                    )'
        );

        if ($result === false) {
            throw new RuntimeException('Processed tasks registry table has not been created.');
        }
    }

}