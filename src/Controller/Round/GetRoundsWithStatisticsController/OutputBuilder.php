<?php

namespace App\Controller\Round\GetRoundsWithStatisticsController;

use App\Controller\Round\GetRoundsWithStatisticsController\Output\Game;
use App\Controller\Round\GetRoundsWithStatisticsController\Output\Round;
use App\Entity\Tournament;
use App\Service\Tournament\ChampionshipChancesPredictor\ChampionshipChancesProvider;
use App\Service\Tournament\TournamentTableProvider\TournamentTableProvider;
use App\Entity\Game as GameEntity;
use App\Entity\Round as RoundEntity;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;
use App\Service\Tournament\ChampionshipChancesPredictor\Output\ChampionshipChancesResult;

class OutputBuilder
{
    private TournamentTableProvider $tournamentTableProvider;
    private ChampionshipChancesProvider $championshipChancesProvider;

    public function __construct(
        TournamentTableProvider $tournamentTableProvider,
        ChampionshipChancesProvider $championshipChancesProvider
    ) {
        $this->tournamentTableProvider = $tournamentTableProvider;
        $this->championshipChancesProvider = $championshipChancesProvider;
    }

    /**
     * @return Game[]
     */
    public function build(Tournament $tournament): array
    {
        $result = [];
        foreach ($tournament->getRounds() as $roundEntity) {
            $games = $this->createGames($roundEntity);
            $tournamentTableForRound = $roundEntity->isFinished()
                ? $this->tournamentTableProvider->getTournamentTableForRound($tournament, $roundEntity->getPosition())
                : null;

            try {
                $championshipChances = $roundEntity->isFinished()
                    ? $this->championshipChancesProvider->getChances($tournament, $roundEntity->getPosition())
                    : null;
            } catch (\LogicException $e) {
                $championshipChances = null;
            }

            $result[] = $this->createRound($roundEntity, $games, $tournamentTableForRound, $championshipChances);
        }

        return $result;
    }

    private function createGame(GameEntity $gameEntity): Game
    {
        return (new Game())
            ->setGameGuid($gameEntity->getGuid())
            ->setStatus($gameEntity->getStatus())
            ->setHomeTeamGuid($gameEntity->getHomeTeam()->getGuid())
            ->setHomeTeamTitle($gameEntity->getHomeTeam()->getTitle())
            ->setGuestTeamGuid($gameEntity->getGuestTeam()->getGuid())
            ->setGuestTeamTitle($gameEntity->getGuestTeam()->getTitle())
            ->setHomeGoals($gameEntity->getHomeGoals())
            ->setGuestGoals($gameEntity->getGuestGoals());
    }

    /**
     * @return Game[]
     */
    protected function createGames(RoundEntity $roundEntity): array
    {
        $games = [];
        foreach ($roundEntity->getGames() as $gameEntity) {
            $games[] = $this->createGame($gameEntity);
        }
        return $games;
    }

    /**
     * @param Game[]
     */
    protected function createRound(
        RoundEntity $roundEntity,
        array $games,
        ?TournamentTable $tournamentTableForRound,
        ?ChampionshipChancesResult $championshipChances
    ): Round {
        return (new Round())
            ->setPosition($roundEntity->getPosition())
            ->setStatus($roundEntity->getStatus())
            ->setGames($games)
            ->setTournamentTable($tournamentTableForRound)
            ->setChampionshipChances($championshipChances);
    }
}
