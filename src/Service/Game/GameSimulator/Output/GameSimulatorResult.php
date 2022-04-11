<?php

namespace App\Service\Game\GameSimulator\Output;

use App\Entity\Contract\GameInterface;

final class GameSimulatorResult implements GameInterface
{
    private string $homeTeamGuid;
    private string $guestTeamGuid;
    private int $homeGoals;
    private int $guestGoals;

    public function __construct(string $homeTeamGuid, string $guestTeamGuid, int $homeGoals, int $guestGoals)
    {
        $this->homeTeamGuid = $homeTeamGuid;
        $this->guestTeamGuid = $guestTeamGuid;
        $this->homeGoals = $homeGoals;
        $this->guestGoals = $guestGoals;
    }

    public function getHomeGoals(): int
    {
        return $this->homeGoals;
    }

    public function getGuestGoals(): int
    {
        return $this->guestGoals;
    }

    public function getStatus(): string
    {
        return 'finished';
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
