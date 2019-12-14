<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\commands;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateProcessedTasksRegistryTableCommand extends CommandWitProcessedTasksRegistryType
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->getProcessedTasksRegistry($input)->initialize();
            $output->writeln('Processed tasks registry successfully created.');

            return 1;
        } catch (Throwable $throwable) {
            $output->writeln('Creation of processed tasks registry failed');
            $output->writeln($throwable->getMessage());

            return 0;
        }
    }

    protected function name(): string
    {
        return 'create';
    }

}