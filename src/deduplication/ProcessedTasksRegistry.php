<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication;


use Lukasz93P\tasksQueue\deduplication\exceptions\RegistrySavingFailed;
use Lukasz93P\tasksQueue\deduplication\exceptions\RegistryUnavailable;
use RuntimeException;

interface ProcessedTasksRegistry
{
    /**
     * @throws RuntimeException
     */
    public function initialize(): void;

    /**
     * @param string $taskId
     * @throws RegistrySavingFailed
     * @throws RegistryUnavailable
     */
    public function save(string $taskId): void;

    /**
     * @param string $taskId
     * @return bool
     * @throws RegistryUnavailable
     */
    public function exists(string $taskId): bool;

    /**
     * @param int $daysNumber
     * @return void
     * @throws RegistryUnavailable
     */
    public function removeEntriesOlderThan(int $daysNumber): void;
}