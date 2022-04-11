<?php

namespace App\Service\Tournament\ChampionshipChancesPredictor\Output;

class ChampionshipChancesResult
{
    private array $probabilityOfWinByTeam;

    public function __construct(array $probabilityOfWinByTeam)
    {
        $this->probabilityOfWinByTeam = $probabilityOfWinByTeam;
    }

    public function getProbabilityOfWinByTeam(): array
    {
        return $this->probabilityOfWinByTeam;
    }
}
