<?php

namespace App\Service\Round\RoundSimulator;

use App\Entity\Round;

interface RoundSimulatorInterface
{
    public function simulate(Round $round): void;
}
