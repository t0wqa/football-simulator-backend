<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(name="tournament")
 */
class Tournament
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
     * @ORM\ManyToOne(targetEntity="Round")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="guid", onDelete="SET NULL")
     */
    private ?Round $currentRound = null;

    /**
     * @var Collection<TournamentTeam>|TournamentTeam[]
     * @ORM\OneToMany(targetEntity="TournamentTeam", mappedBy="tournament")
     */
    private Collection $tournamentTeams;

    /**
     * @ORM\OneToMany(targetEntity="Round", mappedBy="tournament")
     */
    private Collection $rounds;

    public function __construct()
    {
        $this->tournamentTeams = new ArrayCollection();
        $this->rounds = new ArrayCollection();
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

    public function getCurrentRound(): ?Round
    {
        return $this->currentRound;
    }

    public function setCurrentRound(?Round $currentRound): self
    {
        $this->currentRound = $currentRound;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getParticipatingTeamGuidsRandomlyOrdered(): array
    {
        $teamGuids = $this->tournamentTeams->map(function (TournamentTeam $tournamentTeam) {
            return $tournamentTeam->getTeam()->getGuid();
        })->toArray();
        shuffle($teamGuids);

        return $teamGuids;
    }

    public function getTeamByGuid(string $guid): Team
    {
        return $this->tournamentTeams->map(function (TournamentTeam $tournamentTeam) {
            return $tournamentTeam->getTeam();
        })
            ->filter(function (Team $team) use ($guid) {
                return $team->getGuid() === $guid;
            })->first();
    }

    public function getTournamentTeams(): Collection
    {
        return $this->tournamentTeams;
    }

    /**
     * @return Collection<Team>|Team[]
     */
    public function getParticipatingTeams(): Collection
    {
        return $this->tournamentTeams->map(function (TournamentTeam $tournamentTeam) {
            return $tournamentTeam->getTeam();
        });
    }

    public function setTournamentTeams(Collection $tournamentTeams): self
    {
        $this->tournamentTeams = $tournamentTeams;
        return $this;
    }

    /**
     * @return Collection<Round>|Round[]
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    public function setRounds(Collection $rounds): self
    {
        $this->rounds = $rounds;
        return $this;
    }
}
