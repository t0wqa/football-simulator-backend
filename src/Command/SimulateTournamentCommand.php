<?php

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Service\Round\RoundSimulator\RoundSimulatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimulateTournamentCommand extends Command
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

        $this->setName('app:simulate-tournament');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => 'e488d3d6-f179-45b2-bd75-b9081e6a76f0']);
        foreach ($tournament->getRounds() as $round) {
            $this->roundSimulator->simulate($round);
            echo 'Simulated round ' . $round->getPosition() . PHP_EOL;
        }

        return 0;
    }
}
