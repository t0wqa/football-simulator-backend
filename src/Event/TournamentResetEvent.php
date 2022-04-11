<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class TournamentResetEvent extends Event
{
    public const EVENT_KEY = 'tournament.reset';

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
