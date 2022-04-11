<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(name="tournament_team", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique__tournament_team__tournament_team", columns={"tournament", "team"})
 * })
 */
class TournamentTeam
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private string $guid;

    /**
     * @ORM\ManyToOne(targetEntity="Tournament")
     * @ORM\JoinColumn(referencedColumnName="guid", name="tournament")
     */
    private Tournament $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="guid", name="team")
     */
    private Team $team;

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): self
    {
        $this->tournament = $tournament;

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
}
