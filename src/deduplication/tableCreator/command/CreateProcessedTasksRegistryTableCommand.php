<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\deduplication\tableCreator\command;


use Lukasz93P\tasksQueue\deduplication\tableCreator\ProcessedTasksTableCreatorFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateProcessedTasksRegistryTableCommand extends Command
{
    private const ARGUMENT_DATABASE_TYPE = 'database';

    public function configure(): void
    {
        $this->setName('create')
            ->addArgument(self::ARGUMENT_DATABASE_TYPE, InputArgument::OPTIONAL, '', ProcessedTasksTableCreatorFactory::TYPE_MY_SQL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            ProcessedTasksTableCreatorFactory::make($input->getArgument(self::ARGUMENT_DATABASE_TYPE))
                ->createTable();
            $output->writeln('Processed tasks registry successfully created.');

            return 1;
        } catch (Throwable $throwable) {
            $output->writeln('Creation of processed tasks registry failed');
            $output->writeln($throwable->getMessage());

            return 0;
        }
    }

}