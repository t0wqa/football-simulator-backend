<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(name="team_statistics")
 */
class TeamStatistics
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private string $guid;

    /**
     * @ORM\OneToOne(targetEntity="Team", inversedBy="teamStatistics")
     * @ORM\JoinColumn(referencedColumnName="guid", name="team")
     */
    private Team $team;

    /**
     * @ORM\Column(type="integer")
     */
    private int $winsCount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $drawsCount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $defeatsCount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $goalsScored;

    /**
     * @ORM\Column(type="integer")
     */
    private int $goalsMissed;

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getGamesPlayedCount(): int
    {
        return $this->winsCount + $this->defeatsCount + $this->drawsCount;
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

    public function getDrawsCount(): int
    {
        return $this->drawsCount;
    }

    public function setDrawsCount(int $drawsCount): self
    {
        $this->drawsCount = $drawsCount;

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

    public function getAverageGoalsScored(): float
    {
        return $this->goalsScored / $this->getGamesPlayedCount();
    }

    public function getAverageGoalsMissed(): float
    {
        return $this->goalsMissed / $this->getGamesPlayedCount();
    }
}
