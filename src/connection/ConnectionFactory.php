<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\connection;


use mysqli;

class ConnectionFactory
{
    public static function create(string $type): Connection
    {
        return new class implements Connection {
            /**
             * @var mysqli
             */
            private $connection;

            public function query(string $query)
            {
                $this->initilize();

                return $this->connection->query($query);
            }

            private function initilize(): void
            {
                $this->connection = $this->connection
                    ?? new mysqli(
                        getenv('TASKS_DEDUPLICATION_DATABASE_HOST') ?: 'localhost',
                        getenv('TASKS_DEDUPLICATION_DATABASE_USER') ?: 'root',
                        getenv('TASKS_DEDUPLICATION_DATABASE_PASSWORD') ?: '',
                        getenv('TASKS_DEDUPLICATION_DATABASE_NAME') ?: 'code_quality_pacage_lifecycle',
                        (int)(getenv('TASKS_DEDUPLICATION_DATABASE_PORT') ?: 3306),
                    );
            }

        };
    }
}