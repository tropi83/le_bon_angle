<?php

namespace App\Command;

use App\Repository\AdvertRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeletePublishedCommand extends Command
{
    protected static $defaultName = 'app:delete-published';
    private string $commandName = 'app:delete-published';
    private AdvertRepository $advertRepository;


    public function __construct(AdvertRepository $advertRepository)
    {
        $this->advertRepository = $advertRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Removes all published adverts from <days> until today')
            ->addArgument('days', InputArgument::REQUIRED, 'Number of days (int)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);
            $days = $input->getArgument('days');

            if (!preg_match('/^\d+$/', $days)) {
                throw new \Exception("<day> parameter must be an integer");
            }

            $date = new \DateTime();
            $date->modify("-".$days."day");
            $nbDeletions = $this->advertRepository->deletePublishedByDate($date);
            $io->success($nbDeletions.' published publications successfully removed');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage().', see '. $this->commandName . ' --help can help you.');
            return Command::INVALID;
        }
    }
}
