<?php

namespace App\Service\Tournament\ChampionshipChancesPredictor;

use App\Entity\Tournament;
use App\Service\Tournament\ChampionshipChancesPredictor\Output\ChampionshipChancesResult;

interface ChampionshipChancesPredictorInterface
{
    public function predictChances(Tournament $tournament, ?int $atRound = null): ChampionshipChancesResult;
}
