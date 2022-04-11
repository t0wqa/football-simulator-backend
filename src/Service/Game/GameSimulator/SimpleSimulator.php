<?php

namespace App\Service\Game\GameSimulator;

use App\Entity\Game;
use App\Service\Game\GameSimulator\Output\GameSimulatorResult;

class SimpleSimulator implements GameSimulatorInterface
{
    public function simulate(Game $game): GameSimulatorResult
    {
        $homeGoals = $this->getGoals();
        $guestGoals = $this->getGoals();

        return new GameSimulatorResult($game->getHomeTeamGuid(), $game->getGuestTeamGuid(), $homeGoals, $guestGoals);
    }

    private function getGoals()
    {
        $goalsCount = 0;

        if (mt_rand(1, 2) === 1) {
            $goalsCount = 1;
        } else {
            return 0;
        }

        if (mt_rand(1, 4) === 1) {
            $goalsCount = 2;
        }

        if (mt_rand(1, 6) === 1) {
            $goalsCount = 3;
        }

        if (mt_rand(1, 8) === 1) {
            $goalsCount = 4;
        }

        if (mt_rand(1, 10) === 1) {
            $goalsCount = mt_rand(5, 8);
        }

        return $goalsCount;
    }
}
