<?php

namespace App\Service\Round\ScheduleCreator;

use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Tournament;
use App\Event\TournamentResetEvent;
use App\Service\Round\ScheduleGenerator\ScheduleGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use App\Service\Round\ScheduleGenerator\DTO\Game as GameDTO;

class ScheduleCreator
{
    private ScheduleGeneratorInterface $scheduleGenerator;
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ScheduleGeneratorInterface $scheduleGenerator,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->scheduleGenerator = $scheduleGenerator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createSchedule(Tournament $tournament)
    {
        $this->clearRoundsAndGames($tournament);

        $scheduleGenerationResult = $this->scheduleGenerator->generateSchedule($tournament);
        $roundEntities = [];
        foreach ($scheduleGenerationResult->getRounds() as $roundNumber => $round) {
            $roundPosition = $roundNumber + 1;
            $roundEntity = $this->createRoundEntity($roundPosition, $tournament);
            $this->entityManager->persist($roundEntity);
            $roundEntities[] = $roundEntity;

            foreach ($round->getGames() as $game) {
                $gameEntity = $this->createGameEntity($tournament, $game, $roundEntity);
                $this->entityManager->persist($gameEntity);
            }
        }

        $this->setNextRounds($roundEntities);
        $tournament->setCurrentRound($roundEntities[0]);

        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new TournamentResetEvent($tournament->getGuid()), TournamentResetEvent::EVENT_KEY);
    }

    private function clearRoundsAndGames(Tournament $tournament): void
    {
        foreach ($tournament->getRounds() as $round) {
            $this->entityManager->remove($round);
        }

        $this->entityManager->flush();
    }

    private function setNextRounds(array $roundEntities): void
    {
        /** @var Round $roundEntity */
        foreach ($roundEntities as $key => $roundEntity) {
            if ($key === (count($roundEntities) - 1)) {
                continue;
            }

            $roundEntity->setNextRound($roundEntities[$key + 1]);
        }
    }

    protected function createRoundEntity(int $roundNumber, Tournament $tournament): Round
    {
        $roundEntity = new Round();
        $roundEntity
            ->setStatus(Round::STATUS_PLANNING)
            ->setPosition($roundNumber)
            ->setTournament($tournament);

        return $roundEntity;
    }

    protected function createGameEntity(Tournament $tournament, GameDTO $game, Round $roundEntity): Game
    {
        $gameEntity = new Game();
        $gameEntity
            ->setHomeTeam($tournament->getTeamByGuid($game->getHomeTeamGuid()))
            ->setGuestTeam($tournament->getTeamByGuid($game->getGuestTeamGuid()))
            ->setStatus(Game::STATUS_PLANNING)
            ->setRound($roundEntity);

        return $gameEntity;
    }
}
