<?php

namespace App\Service\Tournament\TournamentTableBuilder\Output;

use App\Service\Tournament\TournamentTableBuilder\DTO\TournamentTableRow;

class TournamentTable
{
    /**
     * @var TournamentTableRow[]
     */
    private array $tournamentTableRows;

    public function __construct(array $tournamentTableRows)
    {
        $this->tournamentTableRows = $tournamentTableRows;
    }

    public function setTournamentTableRows(array $tournamentTableRows): self
    {
        $this->tournamentTableRows = $tournamentTableRows;

        return $this;
    }

    public function getTournamentTableRows(): array
    {
        return $this->tournamentTableRows;
    }
}
