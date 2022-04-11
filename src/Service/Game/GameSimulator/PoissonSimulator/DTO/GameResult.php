<?php

namespace App\Service\Game\GameSimulator\PoissonSimulator\DTO;

class GameResult
{
    private int $teamAResult;
    private int $TeamBResult;

    public function __construct(int $teamAResult, int $TeamBResult)
    {
        $this->teamAResult = $teamAResult;
        $this->TeamBResult = $TeamBResult;
    }

    public function getTeamAResult(): int
    {
        return $this->teamAResult;
    }

    public function getTeamBResult(): int
    {
        return $this->TeamBResult;
    }
}
