<?php

namespace App\Service\Round\RoundSimulator;

use App\Entity\Game;
use App\Entity\Round;
use App\Event\RoundCompletedEvent;
use App\Service\Game\GameSimulator\GameSimulatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RoundSimulator implements RoundSimulatorInterface
{
    private GameSimulatorInterface $gameSimulator;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        GameSimulatorInterface $gameSimulator,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->gameSimulator = $gameSimulator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function simulate(Round $round): void
    {
        foreach ($round->getGames() as $game) {
            $simulationResult = $this->gameSimulator->simulate($game);

            $game
                ->setHomeGoals($simulationResult->getHomeGoals())
                ->setGuestGoals($simulationResult->getGuestGoals())
                ->setStatus(Game::STATUS_FINISHED);
        }

        $round->setStatus(Game::STATUS_FINISHED);
        $round->getTournament()->setCurrentRound($round->getNextRound());

        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new RoundCompletedEvent($round->getGuid()), RoundCompletedEvent::EVENT_KEY);
    }

}
