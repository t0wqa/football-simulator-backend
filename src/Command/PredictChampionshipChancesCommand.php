<?php

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Service\Tournament\ChampionshipChancesPredictor\ChampionshipChancesPredictorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PredictChampionshipChancesCommand extends Command
{
    private ChampionshipChancesPredictorInterface $championshipChancesPredictor;
    private TournamentRepository $tournamentRepository;

    public function __construct(ChampionshipChancesPredictorInterface $championshipChancesPredictor, TournamentRepository $tournamentRepository)
    {
        parent::__construct();

        $this->championshipChancesPredictor = $championshipChancesPredictor;
        $this->tournamentRepository = $tournamentRepository;
    }

    protected function configure()
    {
        parent::configure();

        $this->setName('app:predict-chances');
        $this->addOption('atRound', null, InputOption::VALUE_OPTIONAL, 'At round');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => 'e488d3d6-f179-45b2-bd75-b9081e6a76f0']);
        $chances = $this->championshipChancesPredictor->predictChances($tournament, $input->getOption('atRound'));

        print_r($chances->getProbabilityOfWinByTeam());

        return 0;
    }
}
