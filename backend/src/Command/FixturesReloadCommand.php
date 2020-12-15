<?php

declare(strict_types=1);

namespace App\Command;

use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesReloadCommand extends Command
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string
     */
    protected static $defaultName = 'app:fixturesReload';

    protected function configure(): void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:fixturesReload')
            // the short description shown while running "php bin/console list"
            ->setDescription('Drop/Create Database and load Fixtures ....')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to load dummy data by recreating database and loading fixtures...');
    }

    /**
     * @phpcsSuppress() SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();

        if (! $application) {
            throw new RuntimeException('Null pointer exception.');
        }

        $application->setAutoExit(false);

        $this->dropDatabase($output, $application);
        $this->createDatabase($output, $application);
//        $this->schemaUpdate($output, $application);
        $this->runMigration($output, $application);
        $this->loadFixtures($output, $application);

        return Command::SUCCESS;
    }

    protected function dropDatabase(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:database:drop', '--force' => true]));
    }

    protected function createDatabase(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:database:create', '--if-not-exists' => true]));
    }

    protected function schemaUpdate(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********         Updating Schema         *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true]));
    }

    protected function runMigration(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********           Run Migration         *********',
            '===================================================',
            '',
        ]);

        $application->run(new ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]));
    }

    protected function loadFixtures(OutputInterface $output, Application $application): void
    {
        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        //Loading Fixtures
        $application->run(new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction' => true]));
    }
}
