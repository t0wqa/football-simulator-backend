<?php

namespace App\Service\Game\GameSimulator;

use App\Entity\Game;
use App\Service\Game\GameSimulator\Output\GameSimulatorResult;

interface GameSimulatorInterface
{
    public function simulate(Game $game): GameSimulatorResult;
}
