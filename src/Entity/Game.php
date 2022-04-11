<?php

namespace App\Entity;

use App\Entity\Contract\GameInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity
 * @ORM\Table(name="game", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique__game__round_home_team_guest_team", columns={"round", "home_team", "guest_team"})
 * })
 */
class Game implements GameInterface
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private string $guid;

    /**
     * @ORM\ManyToOne(targetEntity="Round")
     * @ORM\JoinColumn(referencedColumnName="guid", name="round", onDelete="CASCADE")
     */
    private Round $round;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="guid", name="home_team")
     */
    private Team $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="guid", name="guest_team")
     */
    private Team $guestTeam;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    /**
     * @ORM\Column(type="integer")
     */
    private int $homeGoals = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $guestGoals = 0;

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getRound(): Round
    {
        return $this->round;
    }

    public function setRound(Round $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getGuestTeam(): Team
    {
        return $this->guestTeam;
    }

    public function setGuestTeam(Team $guestTeam): self
    {
        $this->guestTeam = $guestTeam;

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

    public function getHomeTeamGuid(): string
    {
        return $this->homeTeam->getGuid();
    }

    public function getGuestTeamGuid(): string
    {
        return $this->guestTeam->getGuid();
    }


}
