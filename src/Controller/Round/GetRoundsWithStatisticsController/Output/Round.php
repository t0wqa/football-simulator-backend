<?php

namespace App\Controller\Round\GetRoundsWithStatisticsController\Output;

use App\Service\Tournament\ChampionshipChancesPredictor\Output\ChampionshipChancesResult;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;

class Round
{
    private int $position;

    private string $status;

    /**
     * @var Game[]
     */
    private array $games;

    private ?TournamentTable $tournamentTable = null;

    private ?ChampionshipChancesResult $championshipChances = null;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getGames(): array
    {
        return $this->games;
    }

    public function setGames(array $games): self
    {
        $this->games = $games;

        return $this;
    }

    public function getTournamentTable(): ?TournamentTable
    {
        return $this->tournamentTable;
    }

    public function setTournamentTable(?TournamentTable $tournamentTable): self
    {
        $this->tournamentTable = $tournamentTable;

        return $this;
    }

    public function getChampionshipChances(): ?ChampionshipChancesResult
    {
        return $this->championshipChances;
    }

    public function setChampionshipChances(?ChampionshipChancesResult $championshipChances): self
    {
        $this->championshipChances = $championshipChances;

        return $this;
    }
}
