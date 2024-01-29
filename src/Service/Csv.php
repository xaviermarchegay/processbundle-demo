<?php

declare(strict_types=1);

namespace App\Service;

use Faker\Factory;
use Faker\Generator;
use League\Csv\Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;

class Csv
{
    public const ROWS = 10_000_000;

    private Generator $faker;

    public function __construct(private readonly KernelInterface $kernel)
    {
        $this->faker = Factory::create();
    }

    public function getFilename(): string
    {
        return $this->kernel->getProjectDir() . '/big_file.csv';
    }

    public function getFlowFilename(): string
    {
        return $this->kernel->getProjectDir() . '/computed_flow_big_file.csv';
    }

    public function create(): int
    {

        $writer = Writer::createFromPath($this->getFilename(), 'w+');

        $writer->insertOne(['sku', 'qty', 'date_next_sale', 'statut', 'email_resp']);

        $i = 1;
        do {
            $writer->insertOne($this->getRandomRow());
            $i++;
        } while($i <= self::ROWS);

        return Command::SUCCESS;
    }

    private function getRandomRow(): array
    {
        return [
            $this->faker->ean8(),
            $this->faker->randomNumber(5),
            $this->faker->iso8601(),
            $this->faker->randomElement([20, 30, 50, 80, 90]),
            $this->faker->email(),
        ];
    }
}