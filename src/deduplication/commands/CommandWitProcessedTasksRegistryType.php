<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\commands;


use Lukasz93P\tasksQueue\deduplication\ProcessedTasksRegistry;
use Lukasz93P\tasksQueue\deduplication\ProcessedTasksRegistryFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

abstract class CommandWitProcessedTasksRegistryType extends Command
{
    protected const ARGUMENT_DATABASE_TYPE = 'processed-tasks-registry-type';

    protected function configure(): void
    {
        $this->setName("deduplication-{$this->name()}")
            ->addArgument(self::ARGUMENT_DATABASE_TYPE, InputArgument::OPTIONAL, '', ProcessedTasksRegistryFactory::TYPE_MY_SQL);
    }

    protected function getProcessedTasksRegistry(InputInterface $input): ProcessedTasksRegistry
    {
        return ProcessedTasksRegistryFactory::fromType($input->getArgument(self::ARGUMENT_DATABASE_TYPE));
    }

    abstract protected function name(): string;
}