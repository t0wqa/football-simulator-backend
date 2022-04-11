<?php

namespace App\Service\Round\ScheduleCreator;

use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Tournament;
use App\Service\Round\ScheduleGenerator\ScheduleGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleCreator
{
    private ScheduleGeneratorInterface $scheduleGenerator;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ScheduleGeneratorInterface $scheduleGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->scheduleGenerator = $scheduleGenerator;
        $this->entityManager = $entityManager;
    }

    public function createSchedule(Tournament $tournament)
    {
        $this->clearRoundsAndGames($tournament);
        $scheduleGenerationResult = $this->scheduleGenerator->generateSchedule($tournament);

        $roundEntities = [];
        foreach ($scheduleGenerationResult->getRounds() as $roundNumber => $round) {
            $roundEntity = new Round();
            $roundEntity
                ->setStatus('planning')
                ->setPosition($roundNumber + 1)
                ->setTournament($tournament);

            $this->entityManager->persist($roundEntity);
            $roundEntities[] = $roundEntity;

            foreach ($round->getGames() as $game) {
                $gameEntity = new Game();
                $gameEntity
                    ->setHomeTeam($tournament->getTeamByGuid($game->getHomeTeamGuid()))
                    ->setGuestTeam($tournament->getTeamByGuid($game->getGuestTeamGuid()))
                    ->setStatus('planning')
                    ->setRound($roundEntity);

                $this->entityManager->persist($gameEntity);
            }
        }

        /** @var Round $roundEntity */
        foreach ($roundEntities as $key => $roundEntity) {
            if ($key === (count($roundEntities) - 1)) {
                continue;
            }

            $roundEntity->setNextRound($roundEntities[$key + 1]);
        }
        $tournament->setCurrentRound($roundEntities[0]);

        $this->entityManager->flush();
    }

    private function clearRoundsAndGames(Tournament $tournament): void
    {
        foreach ($tournament->getRounds() as $round) {
            $this->entityManager->remove($round);
        }

        $this->entityManager->flush();
    }
}
