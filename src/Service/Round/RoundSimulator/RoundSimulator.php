<?php

namespace App\Service\Round\RoundSimulator;

use App\Entity\Round;
use App\Service\Game\GameSimulator\GameSimulatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class RoundSimulator implements RoundSimulatorInterface
{
    private GameSimulatorInterface $gameSimulator;
    private EntityManagerInterface $entityManager;

    public function __construct(GameSimulatorInterface $gameSimulator, EntityManagerInterface $entityManager)
    {
        $this->gameSimulator = $gameSimulator;
        $this->entityManager = $entityManager;
    }

    public function simulate(Round $round): void
    {
        foreach ($round->getGames() as $game) {
            $simulationResult = $this->gameSimulator->simulate($game);

            $game
                ->setHomeGoals($simulationResult->getHomeGoals())
                ->setGuestGoals($simulationResult->getGuestGoals())
                ->setStatus('finished');
        }

        $round->setStatus('finished');
        $round->getTournament()->setCurrentRound($round->getNextRound());

        $this->entityManager->flush();
    }

}
