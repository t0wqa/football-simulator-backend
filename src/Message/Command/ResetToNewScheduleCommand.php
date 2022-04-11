<?php

namespace App\Message\Command;

final class ResetToNewScheduleCommand
{
    private string $tournamentGuid;

    public function __construct(string $tournamentGuid)
    {
        $this->tournamentGuid = $tournamentGuid;
    }

    public function getTournamentGuid(): string
    {
        return $this->tournamentGuid;
    }
}
