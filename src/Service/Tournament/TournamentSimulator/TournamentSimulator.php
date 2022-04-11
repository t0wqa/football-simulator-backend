<?php

namespace App\Service\Tournament\TournamentSimulator;

use App\Entity\Tournament;
use App\Service\Round\RoundSimulator\RoundSimulatorInterface;

class TournamentSimulator
{
    private RoundSimulatorInterface $roundSimulator;

    public function __construct(RoundSimulatorInterface $roundSimulator)
    {
        $this->roundSimulator = $roundSimulator;
    }

    public function simulate(Tournament $tournament): void
    {
        while (true) {
            $nextRound = $tournament->getNextRound();
            if (null === $nextRound) {
                break;
            }

            $this->roundSimulator->simulate($nextRound);
        }
    }
}
