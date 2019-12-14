<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\commands;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class RemoveOldEntriesFromProcessedTasksRegistryCommand extends CommandWitProcessedTasksRegistryType
{
    private const ARGUMENT_DELETE_ENTRIES_OLDER_THAN_DAYS_NUMBER = 'num-days';

    protected function configure(): void
    {
        parent::configure();
        $this->addArgument(self::ARGUMENT_DELETE_ENTRIES_OLDER_THAN_DAYS_NUMBER, InputArgument::OPTIONAL, '', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->getProcessedTasksRegistry($input)->removeEntriesOlderThan((int)$input->getArgument(self::ARGUMENT_DELETE_ENTRIES_OLDER_THAN_DAYS_NUMBER));

            return 1;
        } catch (Throwable $throwable) {
            $output->writeln('Removing old entries from processed tasks registry failed.');
            $output->writeln($throwable->getMessage());

            return 0;
        }
    }

    protected function name(): string
    {
        return 'remove';
    }

}