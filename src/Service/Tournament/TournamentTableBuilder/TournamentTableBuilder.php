<?php

namespace App\Service\Tournament\TournamentTableBuilder;

use App\Entity\Contract\GameInterface;
use App\Entity\Game;
use App\Service\Tournament\TournamentTableBuilder\DTO\TournamentTableRow;
use App\Service\Tournament\TournamentTableBuilder\Input\TournamentTableBuilderInput;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;

class TournamentTableBuilder implements TournamentTableBuilderInterface
{
    public function buildTournamentTable(TournamentTableBuilderInput $input): TournamentTable
    {
        $tournamentTableRows = [];
        foreach ($input->getTournament()->getParticipatingTeams() as $team) {
            $tournamentTableRows[$team->getGuid()] = (new TournamentTableRow())
                ->setTeamGuid($team->getGuid())
                ->setTeamTitle($team->getTitle());
        }

        $gamesByTeam = [];
        foreach ($input->getTournament()->getRounds() as $round) {
            if (null !== $input->getMaxRound() && $round->getPosition() > $input->getMaxRound()) {
                continue;
            }

            foreach ($round->getGames() as $game) {
                if ($game->getStatus() !== Game::STATUS_FINISHED) {
                    continue;
                }

                $gamesByTeam[$game->getHomeTeamGuid()][] = $game;
                $gamesByTeam[$game->getGuestTeamGuid()][] = $game;
            }
        }

        foreach ($input->getAdditionalGames() as $game) {
            $gamesByTeam[$game->getHomeTeamGuid()][] = $game;
            $gamesByTeam[$game->getGuestTeamGuid()][] = $game;
        }

        foreach ($gamesByTeam as $teamGuid => $games) {
            $tournamentTableRow = $tournamentTableRows[$teamGuid];
            /** @var GameInterface $game */
            foreach ($games as $game) {
                $this->setWinsCountAfterGame($teamGuid, $game, $tournamentTableRow);
                $this->setDefeatsCountAfterGame($teamGuid, $game, $tournamentTableRow);
                $this->setDrawsCountAfterGame($game, $tournamentTableRow);

                $this->setGoalsScoredInGame($teamGuid, $game, $tournamentTableRow);
                $this->setGoalsMissedInGame($teamGuid, $game, $tournamentTableRow);
            }
        }

        return new TournamentTable($this->sortTournamentTable($tournamentTableRows));
    }

    /**
     * @param TournamentTableRow[] $tournamentTableRows
     * @return TournamentTableRow[]
     */
    private function sortTournamentTable(array $tournamentTableRows): array
    {
        uasort($tournamentTableRows, function (TournamentTableRow $rowA, TournamentTableRow $rowB) {
           if ($rowA->getScore() !== $rowB->getScore()) {
               return $rowA->getScore() > $rowB->getScore() ? -1 : 1;
           }

           if ($rowA->getWinsCount() !== $rowB->getWinsCount()) {
               return $rowA->getWinsCount() > $rowB->getWinsCount() ? -1 : 1;
           }

           if ($rowA->getGoalsDifference() !== $rowB->getGoalsDifference()) {
               return $rowA->getGoalsDifference() > $rowB->getGoalsDifference() ? -1 : 1;
           }

           return mt_rand(0, 1) === 0 ? -1 : 1;
        });

        return array_values($tournamentTableRows);
    }

    private function setGoalsScoredInGame(string $teamGuid, GameInterface $game, TournamentTableRow $tournamentTableRow): void
    {
        if ($this->isHomeTeam($teamGuid, $game)) {
            $tournamentTableRow->setGoalsScored($tournamentTableRow->getGoalsScored() + $game->getHomeGoals());
            return;
        }

        $tournamentTableRow->setGoalsScored($tournamentTableRow->getGoalsScored() + $game->getGuestGoals());
    }

    private function setGoalsMissedInGame(string $teamGuid, GameInterface $game, TournamentTableRow $tournamentTableRow): void
    {
        if ($this->isHomeTeam($teamGuid, $game)) {
            $tournamentTableRow->setGoalsMissed($tournamentTableRow->getGoalsMissed() + $game->getGuestGoals());
            return;
        }

        $tournamentTableRow->setGoalsMissed($tournamentTableRow->getGoalsMissed() + $game->getHomeGoals());
    }

    private function setWinsCountAfterGame(string $teamGuid, GameInterface $game, TournamentTableRow $tournamentTableRow): void
    {
        if ($this->isDraw($game)) {
            return;
        }

        if ($this->isHomeTeam($teamGuid, $game) && $this->hasHomeTeamWon($game)) {
            $tournamentTableRow->setWinsCount($tournamentTableRow->getWinsCount() + 1);

            return;
        }

        if (!$this->isHomeTeam($teamGuid, $game) && !$this->hasHomeTeamWon($game)) {
            $tournamentTableRow->setWinsCount($tournamentTableRow->getWinsCount() + 1);
        }
    }

    private function setDefeatsCountAfterGame(string $teamGuid, GameInterface $game, TournamentTableRow $tournamentTableRow): void
    {
        if ($this->isDraw($game)) {
            return;
        }

        if ($this->isHomeTeam($teamGuid, $game) && !$this->hasHomeTeamWon($game)) {
            $tournamentTableRow->setDefeatsCount($tournamentTableRow->getDefeatsCount() + 1);

            return;
        }

        if (!$this->isHomeTeam($teamGuid, $game) && $this->hasHomeTeamWon($game)) {
            $tournamentTableRow->setDefeatsCount($tournamentTableRow->getDefeatsCount() + 1);
        }
    }

    private function setDrawsCountAfterGame(GameInterface $game, TournamentTableRow $tournamentTableRow): void
    {
        if ($this->isDraw($game)) {
            $tournamentTableRow->setDrawsCount($tournamentTableRow->getDrawsCount() + 1);
        }
    }

    private function isHomeTeam(string $teamGuid, GameInterface $game): bool
    {
        return $game->getHomeTeamGuid() === $teamGuid;
    }

    private function hasHomeTeamWon(GameInterface $game): bool
    {
        return $game->getHomeGoals() > $game->getGuestGoals();
    }

    private function isDraw(GameInterface $game): bool
    {
        return $game->getHomeGoals() === $game->getGuestGoals();
    }
}
