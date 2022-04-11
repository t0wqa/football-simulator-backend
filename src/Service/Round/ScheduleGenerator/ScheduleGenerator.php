<?php

namespace App\Service\Round\ScheduleGenerator;

use App\Entity\Tournament;
use App\Service\Round\ScheduleGenerator\DTO\Game;
use App\Service\Round\ScheduleGenerator\DTO\Round;
use App\Service\Round\ScheduleGenerator\Output\ScheduleGeneratorResult;

/**
 * The algorithms presented here builds a matrix of teams at each round.
 * Each matrix is of <GAMES_PER_ROUND>X2 size. Every next matrix is built by moving around
 * elements of previous.
 *
 * Each resulted matrix is then used to build pairs of opponent teams.
 *
 * TODO need some refactoring to make it readable, for example, by decomposing the method
 */
class ScheduleGenerator implements ScheduleGeneratorInterface
{
    public function generateSchedule(Tournament $tournament): ScheduleGeneratorResult
    {
        $teamGuids = $tournament->getParticipatingTeamGuidsRandomlyOrdered();

        if (count($teamGuids) % 2 !== 0) {
            throw new \LogicException('Teams number must be even');
        }

        $roundsCount = count($teamGuids) - 1;
        $initialMatrix = $this->createInitialMatrix($teamGuids);

        $allMatrixItems = [];
        for ($roundNumber = 1; $roundNumber <= $roundsCount; $roundNumber++) {
            if ($roundNumber === 1) {
                $allMatrixItems[] = $initialMatrix;
                continue;
            }

            $currentMatrix = $allMatrixItems[count($allMatrixItems) - 1];
            $newMatrix = [];
            foreach ($currentMatrix as $rowKey => $row) {
                foreach ($row as $columnKey => $column) {
                    if (0 === $rowKey && 0 === $columnKey) {
                        $newMatrix[$rowKey][$columnKey] = $column;
                        continue;
                    }

                    if (1 === $rowKey && 0 === $columnKey) {
                        $newMatrix[0][1] = $column;
                        continue;
                    }

                    if ((count($currentMatrix) - 1) === $rowKey && 1 === $columnKey) {
                        $newMatrix[$rowKey][0] = $column;
                        continue;
                    }

                    if (1 === $columnKey) {
                        $newMatrix[$rowKey + 1][$columnKey] = $column;
                        continue;
                    }

                    $newMatrix[$rowKey - 1][$columnKey] = $column;
                }
            }

            $allMatrixItems[] = $newMatrix;
        }

        $reversedAllMatrix = array_reverse($allMatrixItems);
        foreach ($reversedAllMatrix as $matrix) {
            $reversedMatrix = [];
            foreach (array_reverse($matrix) as $row) {
                $reversedMatrix[] = array_reverse($row);
            }

            $allMatrixItems[] = $reversedMatrix;
        }

        $rounds = [];
        foreach ($allMatrixItems as $matrixItem) {
            $games = [];
            foreach ($matrixItem as $row) {
                $games[] = new Game($row[0], $row[1]);
            }

            $rounds[] = new Round($games);
        }

        return new ScheduleGeneratorResult($rounds);
    }

    protected function createInitialMatrix(array $teamGuids): array
    {
        $initialMatrix = [];
        foreach (array_chunk($teamGuids, 2) as $chunk) {
            $initialMatrix[] = $chunk;
        }
        return $initialMatrix;
    }

}
