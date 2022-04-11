<?php

namespace App\Service\Tournament\TournamentTableBuilder;

use App\Entity\Tournament;
use App\Service\Tournament\TournamentTableBuilder\Input\TournamentTableBuilderInput;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;

interface TournamentTableBuilderInterface
{
    public function buildTournamentTable(TournamentTableBuilderInput $input): TournamentTable;
}
