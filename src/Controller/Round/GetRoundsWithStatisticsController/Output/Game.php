<?php

namespace App\Controller\Round\GetRoundsWithStatisticsController\Output;

class Game
{
    private string $gameGuid;

    private string $homeTeamGuid;

    private string $homeTeamTitle;

    private string $guestTeamGuid;

    private string $guestTeamTitle;

    private int $homeGoals;

    private int $guestGoals;

    private string $status;

    public function getGameGuid(): string
    {
        return $this->gameGuid;
    }

    public function setGameGuid(string $gameGuid): self
    {
        $this->gameGuid = $gameGuid;

        return $this;
    }

    public function getHomeTeamGuid(): string
    {
        return $this->homeTeamGuid;
    }

    public function setHomeTeamGuid(string $homeTeamGuid): self
    {
        $this->homeTeamGuid = $homeTeamGuid;

        return $this;
    }

    public function getHomeTeamTitle(): string
    {
        return $this->homeTeamTitle;
    }

    public function setHomeTeamTitle(string $homeTeamTitle): self
    {
        $this->homeTeamTitle = $homeTeamTitle;

        return $this;
    }

    public function getGuestTeamGuid(): string
    {
        return $this->guestTeamGuid;
    }

    public function setGuestTeamGuid(string $guestTeamGuid): self
    {
        $this->guestTeamGuid = $guestTeamGuid;

        return $this;
    }

    public function getGuestTeamTitle(): string
    {
        return $this->guestTeamTitle;
    }

    public function setGuestTeamTitle(string $guestTeamTitle): self
    {
        $this->guestTeamTitle = $guestTeamTitle;

        return $this;
    }

    public function getHomeGoals(): int
    {
        return $this->homeGoals;
    }

    public function setHomeGoals(int $homeGoals): self
    {
        $this->homeGoals = $homeGoals;

        return $this;
    }

    public function getGuestGoals(): int
    {
        return $this->guestGoals;
    }

    public function setGuestGoals(int $guestGoals): self
    {
        $this->guestGoals = $guestGoals;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
