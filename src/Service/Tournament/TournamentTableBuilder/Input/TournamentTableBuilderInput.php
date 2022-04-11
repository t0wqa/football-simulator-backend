<?php

namespace App\Service\Tournament\TournamentTableBuilder\Input;

use App\Entity\Contract\GameInterface;
use App\Entity\Tournament;

class TournamentTableBuilderInput
{
    private Tournament $tournament;

    private ?int $maxRound = null;

    /** @var GameInterface[] */
    private array $additionalGames = [];

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getMaxRound(): ?int
    {
        return $this->maxRound;
    }

    public function setMaxRound(?int $maxRound): self
    {
        $this->maxRound = $maxRound;

        return $this;
    }

    public function getAdditionalGames(): array
    {
        return $this->additionalGames;
    }

    public function setAdditionalGames(array $additionalGames): self
    {
        $this->additionalGames = $additionalGames;

        return $this;
    }
}
