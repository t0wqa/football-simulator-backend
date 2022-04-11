<?php

namespace App\Service\Game\GameSimulator\PoissonSimulator;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Tournament;
use App\Helper\MathHelper;
use App\Repository\TeamStatisticsRepository;
use App\Service\Game\GameSimulator\GameSimulatorInterface;
use App\Service\Game\GameSimulator\Output\GameSimulatorResult;
use App\Service\Game\GameSimulator\PoissonSimulator\DTO\GamePossibilities;
use App\Service\Game\GameSimulator\PoissonSimulator\DTO\GameResult;

class PoissonSimulator implements GameSimulatorInterface
{
    private const SIMULATION_ATTEMPTS_COUNT = 5;
    private const MIN_GOALS_FOR_SIMULATION = 0;
    private const MAX_GOALS_FOR_SIMULATION = 10;

    private TeamStatisticsRepository $teamStatisticsRepository;

    private ?float $averageAttackPower = null;
    private ?float $averageDefensePower = null;

    public function __construct(TeamStatisticsRepository $teamStatisticsRepository)
    {
        $this->teamStatisticsRepository = $teamStatisticsRepository;

    }

    public function simulate(Game $game): GameSimulatorResult
    {
        $this->init($game->getRound()->getTournament());

        $teamAResults = [];
        $teamBResults = [];
        for ($simulationIterationNumber = 0; $simulationIterationNumber < self::SIMULATION_ATTEMPTS_COUNT; $simulationIterationNumber++) {
            $gameResult = $this->tryOneGame($game->getHomeTeam(), $game->getGuestTeam());

            $teamAResults[] = $gameResult->getTeamAResult();
            $teamBResults[] = $gameResult->getTeamBResult();
        }

        $teamATotalScored = $this->getTeamTotalScored($teamAResults);
        $teamBTotalScored = $this->getTeamTotalScored($teamBResults);
        $teamAGoals = round($teamATotalScored / self::SIMULATION_ATTEMPTS_COUNT);
        $teamBGoals = round($teamBTotalScored / self::SIMULATION_ATTEMPTS_COUNT);

        return new GameSimulatorResult(
            $game->getHomeTeamGuid(),
            $game->getGuestTeamGuid(),
            $teamAGoals,
            $teamBGoals
        );
    }

    private function getTeamTotalScored(array $teamResults): int
    {
        $totalScored = 0;

        foreach ($teamResults as $teamResult) {
            $totalScored += $teamResult;
        }

        return $totalScored;
    }

    private function init(Tournament $tournament): void
    {
        if ($this->isInitialized()) {
            return;
        }

        $averageGoalsScored = 0;
        $averageGoalsMissed = 0;

        $teamStatisticItems = $this->teamStatisticsRepository->findAllByTournamentGuid($tournament->getGuid());
        foreach ($teamStatisticItems as $teamStatistics) {
            $averageGoalsScored += $teamStatistics->getGoalsScored() / $teamStatistics->getGamesPlayedCount();
            $averageGoalsMissed += $teamStatistics->getGoalsMissed() / $teamStatistics->getGamesPlayedCount();
        }

        $teamsCount = count($teamStatisticItems);
        $this->averageAttackPower = $averageGoalsScored / $teamsCount;
        $this->averageDefensePower = $averageGoalsMissed / $teamsCount;
    }

    private function isInitialized(): bool
    {
        return null !== $this->averageAttackPower && null !== $this->averageDefensePower;
    }

    private function getTeamAttackPower(Team $team): float
    {
        return $team->getTeamStatistics()->getAverageGoalsScored() / $this->averageAttackPower;
    }

    private function getTeamDefensePower(Team $team): float
    {
        return $team->getTeamStatistics()->getAverageGoalsMissed() / $this->averageDefensePower;
    }

    private function calculateMean(Team $teamA, Team $teamB): float
    {
        return $this->getTeamAttackPower($teamA) * $this->getTeamDefensePower($teamB) * $this->averageAttackPower;
    }

    private function predictGamePossibilities(Team $teamA, Team $teamB): GamePossibilities
    {
        $teamAPoissonDistribution = $this->getPoissonDistribution(
            $this->getPoissonPossibilities($teamA, $teamB)
        );

        $teamBPoissonDistribution = $this->getPoissonDistribution(
            $this->getPoissonPossibilities($teamB, $teamA)
        );

        return new GamePossibilities($teamAPoissonDistribution, $teamBPoissonDistribution);
    }

    private function getPoissonDistribution(array $poissonPossibilities): array
    {
        $currentPossibility = 0;
        $calculatedDistribution = [];

        foreach ($poissonPossibilities as $poissonPossibility) {
            $currentPossibility += $poissonPossibility;
            $calculatedDistribution[] = $currentPossibility;
        }

        return $calculatedDistribution;
    }

    private function getPoissonPossibilities(Team $teamA, Team $teamB): array
    {
        $teamMean = $this->calculateMean($teamA, $teamB);

        return array_map(function(int $goalsScored) use ($teamMean) {
            return pow(M_E, -$teamMean) * pow($teamMean, $goalsScored) / MathHelper::factorial($goalsScored) * 10000;
        }, range(self::MIN_GOALS_FOR_SIMULATION, self::MAX_GOALS_FOR_SIMULATION));
    }

    private function tryOneGame(Team $teamA, Team $teamB): GameResult
    {
        $poissonPossibilities = $this->predictGamePossibilities($teamA, $teamB);

        $teamARand = mt_rand(0, 9999);
        $teamAResult = 0;

        for ($counterOne = 0; $counterOne < count($poissonPossibilities->getTeamAPoissonDistributions()); $counterOne++) {
            if ($teamARand <= $poissonPossibilities->getTeamAPoissonDistributions()[$counterOne]) {
                $teamAResult = $counterOne;
                break;
            }
        }

        $teamBRand = mt_rand(0, 9999);
        $teamBResult = 0;

        for ($counterTwo = 0; $counterTwo < count($poissonPossibilities->getTeamBPoissonDistributions()); $counterTwo++) {
            if ($teamBRand <= $poissonPossibilities->getTeamBPoissonDistributions()[$counterTwo]) {
                $teamBResult = $counterTwo;
                break;
            }
        }

        return new GameResult($teamAResult, $teamBResult);
    }
}
