<?php

declare(strict_types=1);

namespace App\Command;

use Flow\ETL\Loader\StreamLoader\Output;
use Flow\ETL\Row\Schema\Constraint\SameAs;
use Flow\ETL\Row\Schema\Metadata;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Flow\ETL\DSL\bool_schema;
use function Flow\ETL\DSL\data_frame;
use function Flow\ETL\DSL\from_array;
use function Flow\ETL\DSL\int_schema;
use function Flow\ETL\DSL\schema;
use function Flow\ETL\DSL\str_schema;
use function Flow\ETL\DSL\to_output;

#[AsCommand(name: 'test', description: 'Test', )]
class TestCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        data_frame()
            ->read(from_array([
                ['id' => 1, 'name' => 'Product 1', 'active' => true],
                ['id' => 2, 'name' => 'Product 2', 'active' => false],
                ['id' => 3, 'name' => 'Product 3', 'active' => true]
            ]))
            ->validate(
                schema(
                    int_schema('id'),
                    str_schema('name', true),
                    bool_schema('active', false, new SameAs(true), Metadata::empty()->add('key', 'value')),
                )
            )
            ->write(to_output(false, Output::rows_and_schema))
            ->run();

        return Command::SUCCESS;
    }
}
