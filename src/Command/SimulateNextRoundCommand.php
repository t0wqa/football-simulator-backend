<?php

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Service\Round\RoundSimulator\RoundSimulatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimulateNextRoundCommand extends Command
{
    private RoundSimulatorInterface $roundSimulator;
    private TournamentRepository $tournamentRepository;

    public function __construct(RoundSimulatorInterface $roundSimulator, TournamentRepository $tournamentRepository)
    {
        parent::__construct();

        $this->roundSimulator = $roundSimulator;
        $this->tournamentRepository = $tournamentRepository;
    }

    protected function configure()
    {
        parent::configure();

        $this->setName('app:simulate-next-round');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => 'e488d3d6-f179-45b2-bd75-b9081e6a76f0']);
        $currentRound = $tournament->getCurrentRound();
        if ($currentRound === null) {
            die('Tournament is over');
        }

        $this->roundSimulator->simulate($currentRound);
        echo sprintf('Round %d was simulated', $currentRound->getPosition()) . PHP_EOL;

        return 0;
    }
}
