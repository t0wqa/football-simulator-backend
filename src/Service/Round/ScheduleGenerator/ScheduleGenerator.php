<?php

namespace App\Service\Round\ScheduleGenerator;

use App\Entity\Tournament;
use App\Service\Round\ScheduleGenerator\DTO\Game;
use App\Service\Round\ScheduleGenerator\DTO\Round;
use App\Service\Round\ScheduleGenerator\Output\ScheduleGeneratorResult;

class ScheduleGenerator implements ScheduleGeneratorInterface
{
    public function generateSchedule(Tournament $tournament): ScheduleGeneratorResult
    {
        $teamGuids = $tournament->getParticipatingTeamGuidsRandomlyOrdered();
        if (count($teamGuids) % 2 !== 0) {
            throw new \LogicException('Teams number must be even');
        }

        $allGames = [];
        foreach ($teamGuids as $checkingTeamGuid) {
            foreach ($teamGuids as $checkedTeamGuid) {
                if ($checkingTeamGuid === $checkedTeamGuid) {
                    continue;
                }

                $gameKey = sprintf('key%s____%s', $checkingTeamGuid, $checkedTeamGuid);
                if (!array_key_exists($gameKey, $allGames)) {
                    $allGames[$gameKey] = new Game($checkingTeamGuid, $checkedTeamGuid);
                }
            }
        }

        $gamesPerRound = count($teamGuids) / 2;
        $roundsCount = (count($teamGuids) - 1) * 2;

        $gamesCount = 0;
        while ($gamesCount !== ($roundsCount * $gamesPerRound)) {
            $alreadyPlannedGameKeys = [];
            $rounds = [];
            for ($roundNumber = 1; $roundNumber <= $roundsCount; $roundNumber++) {
                /** @var Game[] $gamesLeft */
                $gamesLeft = array_values(
                    array_filter($allGames, function (Game $game, $key) use ($alreadyPlannedGameKeys) {
                        return !in_array($key, $alreadyPlannedGameKeys, true);
                    }, ARRAY_FILTER_USE_BOTH)
                );
                shuffle($gamesLeft);

                $gamesForRound = [];
                $teamsInRound = [];

                for ($gameNumber = 1; $gameNumber <= $gamesPerRound; $gameNumber++) {
                    foreach ($gamesLeft as $gameAvailable) {
                        if (!in_array($gameAvailable->getHomeTeamGuid(), $teamsInRound) && !in_array($gameAvailable->getGuestTeamGuid(), $teamsInRound)) {
                            $teamsInRound[] = $gameAvailable->getHomeTeamGuid();
                            $teamsInRound[] = $gameAvailable->getGuestTeamGuid();

                            $gamesForRound[] = $gameAvailable;
                        }
                    }

                    foreach ($gamesForRound as $gameForRound) {
                        $alreadyPlannedGameKeys[] = sprintf('key%s____%s', $gameForRound->getHomeTeamGuid(), $gameForRound->getGuestTeamGuid());
                    }

                    $rounds[$roundNumber] = new Round($gamesForRound);
                }
            }

            $gamesInRounds = [];
            /** @var Round $round */
            foreach ($rounds as $round) {
                foreach ($round->getGames() as $game) {
                    $gamesInRounds[] = $game;
                }
            }
            $gamesCount = count($gamesInRounds);
            echo $gamesCount . PHP_EOL;
        }

        shuffle($rounds);
        return new ScheduleGeneratorResult($rounds);
    }

}
