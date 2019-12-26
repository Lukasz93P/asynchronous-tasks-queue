<?php
declare(strict_types=1);


namespace Lukasz93P\tasksQueue\outbox\commands;


use Lukasz93P\tasksQueue\outbox\OutboxFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTasksOutboxTable extends Command
{
    protected function configure(): void
    {
        $this->setName('outbox-create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            OutboxFactory::create([])->initialize();
            $output->writeln('Success');

            return 0;
        } catch (Throwable $throwable) {
            $output->writeln($throwable->getMessage());

            return 1;
        }
    }

}