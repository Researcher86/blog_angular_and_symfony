<?php

declare(strict_types=1);

namespace App\Command;

use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Run bin/console app:redis:client
 *
 * @package App\Command
 */
class RedisClientCommand extends Command
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string
     */
    protected static $defaultName = 'app:redis:client';
    private Client $redisClient;

    public function __construct(Client $redisClient)
    {
        parent::__construct();
        $this->redisClient = $redisClient;
    }

    protected function configure(): void
    {
        $this->setDescription('Redis client');
    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note($this->redisClient->getset('test', 'Hello World') ?? '');
//        $io->note($this->redisClient->del('test'));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
