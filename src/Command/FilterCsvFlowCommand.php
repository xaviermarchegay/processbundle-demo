<?php

declare(strict_types=1);

namespace App\Command;

use Flow\ETL\Memory\ArrayMemory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Flow\ETL\Adapter\CSV\from_csv;
use function Flow\ETL\DSL\data_frame;
use function Flow\ETL\DSL\lit;
use function Flow\ETL\DSL\ref;
use function Flow\ETL\DSL\to_memory;

#[AsCommand(name: 'filter_csv_flow', description: 'Filter Csv with Flow',)]
class FilterCsvFlowCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        data_frame()
            ->read(from_csv('./big_file.csv'))
            ->select(
                'sku', 'qty', 'date_next_sale', 'statut', 'email_resp'
            )
            ->withEntries([
                'sku' => ref('sku')->cast('string'),
                'qty' => ref('qty')->cast('integer'),
                'date_next_sale' => ref('date_next_sale'),
                'statut' => ref('statut')->cast('string'),
                'email_resp' => ref('email_resp')->cast('string')
            ])
            ->write(to_memory($memory = new ArrayMemory()))
            ->run();

        var_dump($memory);

        return Command::SUCCESS;
    }
}
