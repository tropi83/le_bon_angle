<?php

namespace App\Command;

use App\Repository\AdvertRepository;
use App\Repository\PictureRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteUnusedPicsCommand extends Command
{
    protected static $defaultName = 'app:delete-unused-pics';
    private string $commandName = 'app:delete-unused-pics';
    private PictureRepository $pictureRepository;


    public function __construct(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = $pictureRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Removes all unused pictures from <days> until today')
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
            $nbDeletions = $this->pictureRepository->deleteUnusedPicsByDate($date);
            $io->success($nbDeletions.'  unused pictures successfully removed');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage().', see '. $this->commandName . ' --help can help you.');
            return Command::INVALID;
        }
    }
}
