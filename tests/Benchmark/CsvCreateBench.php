<?php

declare(strict_types=1);

namespace App\Tests\Benchmark;

class CsvCreateBench
{
    public function benchCsvFlow(): void
    {
        shell_exec('bin/console csv:compute-flow');
    }

    public function benchCsvProcessBundle(): void
    {
        shell_exec('bin/console cl:p:e pb_demo.csv_compute');
    }
}