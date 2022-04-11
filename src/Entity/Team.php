<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * Represents a team. A team can participate in many tournaments
 *
 * @ORM\Entity
 * @ORM\Table(name="team")
 */
class Team
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private string $guid;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    /**
     * @ORM\OneToOne(targetEntity="TeamStatistics", mappedBy="team")
     */
    private TeamStatistics $teamStatistics;

    /**
     * @ORM\OneToMany(targetEntity="TournamentTeam", mappedBy="team")
     */
    private Collection $tournaments;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTeamStatistics(): TeamStatistics
    {
        return $this->teamStatistics;
    }

    public function setTeamStatistics(TeamStatistics $teamStatistics): self
    {
        $this->teamStatistics = $teamStatistics;

        return $this;
    }

    /**
     * @return Collection<TournamentTeam>|TournamentTeam[]
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function setTournaments(Collection $tournaments): self
    {
        $this->tournaments = $tournaments;

        return $this;
    }
}
