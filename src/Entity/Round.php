<?php

namespace App\Entity;

use App\Entity\Contract\GameInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(name="round", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique__round__tournament_position", columns={"tournament", "position"})
 * })
 */
class Round
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
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(type="integer")
     */
    private int $position;

    /**
     * @ORM\OneToOne(targetEntity="Round")
     * @ORM\JoinColumn(referencedColumnName="guid", nullable=true, onDelete="CASCADE")
     */
    private ?Round $nextRound = null;

    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="round")
     */
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getNextRound(): ?Round
    {
        return $this->nextRound;
    }

    public function setNextRound(?Round $nextRound): self
    {
        $this->nextRound = $nextRound;

        return $this;
    }

    /**
     * @return GameInterface[]|Collection
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function areAllGamesFinished(): bool
    {
        foreach ($this->games as $game) {
            if ($game->getStatus() !== 'finished') {
                return false;
            }
        }

        return true;
    }
}
