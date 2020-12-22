<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\CentrifugoService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Run bin/console app:centrifugo:publisher
 *
 * @package App\Command
 */
class CentrifugoPublisherCommand extends Command
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     *
     * @var string
     */
    protected static $defaultName = 'app:centrifugo:publisher';
    private CentrifugoService $centrifugoService;

    public function __construct(CentrifugoService $centrifugoService)
    {
        parent::__construct();
        $this->centrifugoService = $centrifugoService;
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

        $this->centrifugoService->publish('news', ['message' => 'Hello from PHP CLI']);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        return Command::SUCCESS;
    }
}
