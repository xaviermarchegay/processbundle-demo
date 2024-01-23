<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Csv;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(name: 'csv:create', description: 'Create Big Csv',)]
class CsvCreateCommand extends Command
{
    public function __construct(
        private readonly Csv       $csv,
        private readonly Stopwatch $stopwatch,
        string                     $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->stopwatch->start(__METHOD__);

        $this->csv->create();

        $this->stopwatch->stop(__METHOD__);

        $output->writeln((string) $this->stopwatch->getEvent(__METHOD__));

        return Command::SUCCESS;
    }
}
