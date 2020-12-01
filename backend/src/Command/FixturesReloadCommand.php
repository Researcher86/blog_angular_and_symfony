<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesReloadCommand extends Command
{
    protected static $defaultName = 'app:fixturesReload';

    protected function configure()
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = ['command' => 'doctrine:database:drop','--force' => true];
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));


        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = ['command' => 'doctrine:database:create','--if-not-exists' => true];
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

//        $output->writeln([
//            '===================================================',
//            '*********         Updating Schema         *********',
//            '===================================================',
//            '',
//        ]);
//
//        //Create de Schema
//        $options = array('command' => 'doctrine:schema:update',"--force" => true);
//        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********           Run Migration         *********',
            '===================================================',
            '',
        ]);

        //Create de Schema
        $options = ['command' => 'doctrine:migrations:migrate', '--no-interaction' => true];
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        //Loading Fixtures
        $options = ['command' => 'doctrine:fixtures:load','--no-interaction' => true];
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        return Command::SUCCESS;
    }
}
