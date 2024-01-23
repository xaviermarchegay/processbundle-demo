<?php

declare(strict_types=1);

namespace App\Command;

use Faker\Factory;
use League\Csv\Writer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'create_csv', description: 'Create Big Csv', )]
class CreateCsvCommand extends Command
{
    private $faker;

    public function __construct(string $name = null)
    {
        $this->faker = Factory::create();

        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $writer = Writer::createFromPath('/tmp/big_file.csv', 'w+');

        $writer->insertOne(['sku', 'qty', 'date_next_sale', 'statut', 'email_resp']);

        $i = 1;
        do {
            $writer->insertOne($this->getRandomRow());
            $output->writeln(sprintf('%s', $i++));
        } while($i <= 1_000);

        $output->writeln((string)filesize('/tmp/big_file.csv'));

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
