<?php

namespace App\Service\Game\GameSimulator\PoissonSimulator\DTO;

class GamePossibilities
{
    private array $teamAPoissonDistributions;
    private array $teamBPoissonDistributions;

    public function __construct(array $teamAPoissonDistributions, array $teamBPoissonDistributions)
    {
        $this->teamAPoissonDistributions = $teamAPoissonDistributions;
        $this->teamBPoissonDistributions = $teamBPoissonDistributions;
    }

    public function getTeamAPoissonDistributions(): array
    {
        return $this->teamAPoissonDistributions;
    }

    public function getTeamBPoissonDistributions(): array
    {
        return $this->teamBPoissonDistributions;
    }
}
