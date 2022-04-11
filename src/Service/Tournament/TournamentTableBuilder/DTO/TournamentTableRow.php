<?php

namespace App\Service\Tournament\TournamentTableBuilder\DTO;

class TournamentTableRow
{
    private string $teamGuid;

    private string $teamTitle;

    private int $winsCount = 0;

    private int $defeatsCount = 0;

    private int $drawsCount = 0;

    private int $goalsScored = 0;

    private int $goalsMissed = 0;

    public function getTeamGuid(): string
    {
        return $this->teamGuid;
    }

    public function setTeamGuid(string $teamGuid): self
    {
        $this->teamGuid = $teamGuid;

        return $this;
    }

    public function getTeamTitle(): string
    {
        return $this->teamTitle;
    }

    public function setTeamTitle(string $teamTitle): self
    {
        $this->teamTitle = $teamTitle;

        return $this;
    }

    public function getGamesPlayedCount(): int
    {
        return $this->winsCount + $this->drawsCount + $this->defeatsCount;
    }

    public function getWinsCount(): int
    {
        return $this->winsCount;
    }

    public function setWinsCount(int $winsCount): self
    {
        $this->winsCount = $winsCount;

        return $this;
    }

    public function getDefeatsCount(): int
    {
        return $this->defeatsCount;
    }

    public function setDefeatsCount(int $defeatsCount): self
    {
        $this->defeatsCount = $defeatsCount;

        return $this;
    }

    public function getDrawsCount(): int
    {
        return $this->drawsCount;
    }

    public function setDrawsCount(int $drawsCount): self
    {
        $this->drawsCount = $drawsCount;

        return $this;
    }

    public function getGoalsScored(): int
    {
        return $this->goalsScored;
    }

    public function setGoalsScored(int $goalsScored): self
    {
        $this->goalsScored = $goalsScored;

        return $this;
    }

    public function getGoalsMissed(): int
    {
        return $this->goalsMissed;
    }

    public function setGoalsMissed(int $goalsMissed): self
    {
        $this->goalsMissed = $goalsMissed;

        return $this;
    }

    public function getGoalsDifference(): int
    {
        return $this->goalsScored - $this->goalsMissed;
    }

    public function getScore(): int
    {
        return $this->winsCount * 3 + $this->drawsCount;
    }
}
