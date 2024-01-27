<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Csv;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use function Flow\ETL\Adapter\CSV\from_csv;
use function Flow\ETL\Adapter\CSV\to_csv;
use function Flow\ETL\DSL\data_frame;
use function Flow\ETL\DSL\lit;
use function Flow\ETL\DSL\ref;
use function Flow\ETL\DSL\when;

#[AsCommand(name: 'csv:compute-flow', description: 'Compute Csv with Flow',)]
class FilterCsvFlowCommand extends Command
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

        @unlink($this->csv->getFlowFilename());
        data_frame()
            ->read(from_csv($this->csv->getFilename()))
            ->select(
                'sku', 'qty', 'date_next_sale', 'statut', 'email_resp'
            )
            ->withEntries([
                'sku' => ref('sku')->cast('string'),
                'qty' => ref('qty')->cast('integer'),
                'date_next_sale' => ref('date_next_sale'),
                'statut' => ref('statut')->cast('integer'),
                'email_resp' => ref('email_resp')->cast('string')
            ])
            ->withEntry(
                'qty_big',
                when(ref('qty')->greaterThan(lit(1_000)), then: lit(1), else: lit(0))
            )
            ->write(to_csv($this->csv->getFlowFilename()))
            ->run();

        $this->stopwatch->stop(__METHOD__);

        $output->writeln((string) $this->stopwatch->getEvent(__METHOD__));

        return Command::SUCCESS;
    }
}
