<?php

namespace App\Service\Round\ScheduleGenerator\DTO;

final class Game
{
    private string $homeTeamGuid;

    private string $guestTeamGuid;

    public function __construct(string $homeTeamGuid, string $guestTeamGuid)
    {
        $this->homeTeamGuid = $homeTeamGuid;
        $this->guestTeamGuid = $guestTeamGuid;
    }

    public function getHomeTeamGuid(): string
    {
        return $this->homeTeamGuid;
    }

    public function getGuestTeamGuid(): string
    {
        return $this->guestTeamGuid;
    }
}
