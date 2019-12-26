<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\outbox;


use Lukasz93P\objectSerializer\ObjectSerializer;
use Lukasz93P\tasksQueue\connection\Connection;
use Lukasz93P\tasksQueue\outbox\exceptions\TasksAddingFailed;
use Lukasz93P\tasksQueue\PublishableAsynchronousTask;
use Lukasz93P\tasksQueue\queue\Queue;
use RuntimeException;

class MySqlOutbox implements Outbox
{
    private const TABLE_NAME = 'outbox_lukasz93p';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var ObjectSerializer
     */
    private $serializer;

    public function __construct(Connection $connection, ObjectSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function initialize(): void
    {
        $result = $this->connection->query(
            'CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    body TEXT,
                    published BOOLEAN DEFAULT 0,
                    INDEX(published)
                    )'
        );

        if ($result === false) {
            throw new RuntimeException('Tasks outbox table has not been created.');
        }
    }

    /**
     * @param PublishableAsynchronousTask[] $tasks *
     */
    public function add(array $tasks): void
    {
        if (empty($tasks)) {
            return;
        }

        $valuesToInsert = [];
        foreach ($tasks as $task) {
            $valuesToInsert[] = "('{$this->serializer->serialize($task)}')";
        }
        if ($this->connection->query(str_replace('\\', '\\\\', 'INSERT INTO ' . self::TABLE_NAME . ' (body) VALUES ' . implode(', ', $valuesToInsert))) === false) {
            throw TasksAddingFailed::reasonNotKnown();
        }
    }

    public function publish(Queue $queue): void
    {
        $unpublishedTasks = $this->connection->query('SELECT * FROM ' . self::TABLE_NAME . " WHERE published = 0");
        if ($unpublishedTasks === false) {
            throw new RuntimeException('MySql error has occurred');
        }

        $messages = [];
        $ids = [];

        foreach ($unpublishedTasks->fetch_all(MYSQLI_ASSOC) as $row) {
            $messages[] = $this->serializer->deserialize($row['body']);
            $ids[] = $row['id'];
        }

        $queue->enqueue($messages);
        $this->connection->query('UPDATE ' . self::TABLE_NAME . ' SET published = 1 WHERE id IN(' . implode(', ', $ids) . ')');
    }

}