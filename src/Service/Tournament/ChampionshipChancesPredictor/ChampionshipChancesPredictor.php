<?php

namespace App\Service\Tournament\ChampionshipChancesPredictor;

use App\Entity\Contract\GameInterface;
use App\Entity\Game;
use App\Entity\Round;
use App\Entity\Tournament;
use App\Service\Game\GameSimulator\GameSimulatorInterface;
use App\Service\Game\GameSimulator\Output\GameSimulatorResult;
use App\Service\Tournament\ChampionshipChancesPredictor\Output\ChampionshipChancesResult;
use App\Service\Tournament\TournamentTableBuilder\Input\TournamentTableBuilderInput;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;
use App\Service\Tournament\TournamentTableBuilder\TournamentTableBuilder;

/**
 * This class runs N simulations of the tournament (whole or at the given point if atRound argument is passed).
 * Using the result of those simulations, it calculates probability of win for each team.
 */
class ChampionshipChancesPredictor implements ChampionshipChancesPredictorInterface
{
    private const MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION = 3;
    private const SIMULATION_ITERATIONS_COUNT = 30;

    private GameSimulatorInterface $gameSimulator;
    private TournamentTableBuilder $tournamentTableBuilder;

    public function __construct(GameSimulatorInterface $gameSimulator, TournamentTableBuilder $tournamentTableBuilder)
    {
        $this->gameSimulator = $gameSimulator;
        $this->tournamentTableBuilder = $tournamentTableBuilder;
    }

    public function predictChances(Tournament $tournament, ?int $atRound = null): ChampionshipChancesResult
    {
        $roundsCount = count($tournament->getRounds());
        $currentRoundPosition = $tournament?->getCurrentRound()?->getPosition() ?? $roundsCount;
        if ($this->isPredictionProhibited($roundsCount, $currentRoundPosition, $atRound)) {
            throw new \LogicException(sprintf(
                'Minimal rounds until the end of tournament must be at least %d',
                self::MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION
            ));
        }

        $generatedTournamentTables = $this->generateTournamentTables($tournament, $atRound);
        $winningTeams = $this->collectWinningTeams($generatedTournamentTables);
        $probabilityOfWinByTeam = $this->calculateProbabilityOfWinForEachTeam($winningTeams);

        return new ChampionshipChancesResult($probabilityOfWinByTeam);
    }

    /**
     * @return TournamentTable[]
     */
    private function generateTournamentTables(Tournament $tournament, ?int $atRound = null): array
    {
        $generatedTournamentTables = [];
        for ($simulationIteration = 1; $simulationIteration <= self::SIMULATION_ITERATIONS_COUNT; $simulationIteration++) {
            $gameSimulations = $this->runGamesSimulations($tournament, $atRound);

            $tournamentTableBuilderInput = (new TournamentTableBuilderInput())
                ->setTournament($tournament)
                ->setMaxRound($atRound)
                ->setAdditionalGames($gameSimulations);

            $generatedTournamentTables[] = $this->tournamentTableBuilder->buildTournamentTable($tournamentTableBuilderInput);
        }

        return $generatedTournamentTables;
    }

    /**
     * @return GameSimulatorResult[]
     */
    private function runGamesSimulations(Tournament $tournament, ?int $atRound = null): array
    {
        $gameSimulations = [];
        foreach ($tournament->getRounds() as $round) {
            foreach ($round->getGames() as $game) {
                if ($this->shouldGameBeSkipped($game, $round, $atRound)) {
                    continue;
                }

                $gameSimulations[] = $this->gameSimulator->simulate($game);
            }
        }

        return $gameSimulations;
    }

    /**
     * @param TournamentTable[] $tournamentTables
     */
    private function collectWinningTeams(array $tournamentTables): array
    {
        $winningTeams = [];
        foreach ($tournamentTables as $tournamentTable) {
            $winningTeams[] = $tournamentTable->getTournamentTableRows()[0]->getTeamGuid();
        }

        return $winningTeams;
    }

    private function calculateProbabilityOfWinForEachTeam(array $winningTeams): array
    {
        $probabilityOfWinByTeam = [];
        foreach (array_count_values($winningTeams) as $teamGuid => $winsCount) {
            $probabilityOfWinByTeam[$teamGuid] = floor(($winsCount / self::SIMULATION_ITERATIONS_COUNT) * 100);
        }

        return $probabilityOfWinByTeam;
    }

    private function isPredictionProhibited(int $roundsCount, int $currentRoundPosition, ?int $atRound = null): bool
    {
        return $roundsCount - $currentRoundPosition >= self::MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION || (null !== $atRound && $roundsCount - $atRound >= self::MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION);
    }

    private function shouldGameBeSkipped(GameInterface $game, Round $round, ?int $atRound = null): bool
    {
        return $game->getStatus() === Game::STATUS_FINISHED && (null === $atRound || (null !== $atRound && $round->getPosition() <= $atRound));
    }
}
