<?php

namespace App\Service\Tournament\ChampionshipChancesPredictor;

use App\Entity\Tournament;
use App\Service\Game\GameSimulator\GameSimulatorInterface;
use App\Service\Tournament\ChampionshipChancesPredictor\Output\ChampionshipChancesResult;
use App\Service\Tournament\TournamentTableBuilder\Input\TournamentTableBuilderInput;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;
use App\Service\Tournament\TournamentTableBuilder\TournamentTableBuilder;

/**
 * This class just runs 10 simulations of tournament and then returns the average result of those simulations
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
        if ($roundsCount - $currentRoundPosition >= self::MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION || (null !== $atRound && $roundsCount - $atRound >= self::MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION)) {
            throw new \LogicException(sprintf(
                'Minimal rounds until the end of tournament must be at least %d',
                self::MINIMAL_ROUNDS_LEFT_ALLOWED_FOR_PREDICTION
            ));
        }

        /** @var TournamentTable[] $generatedTournamentTables */
        $generatedTournamentTables = [];
        for ($simulationIteration = 1; $simulationIteration <= self::SIMULATION_ITERATIONS_COUNT; $simulationIteration++) {
            $gameSimulations = [];
            foreach ($tournament->getRounds() as $round) {
                foreach ($round->getGames() as $game) {
                    if ($game->getStatus() === 'finished' && (null === $atRound || (null !== $atRound && $round->getPosition() <= $atRound))) {
                        continue;
                    }
                    $gameSimulations[] = $this->gameSimulator->simulate($game);
                }
            }

            $tournamentTableBuilderInput = (new TournamentTableBuilderInput())
                ->setTournament($tournament)
                ->setMaxRound($atRound)
                ->setAdditionalGames($gameSimulations);
            $generatedTournamentTables[] = $this->tournamentTableBuilder->buildTournamentTable($tournamentTableBuilderInput);
        }

        $winningTeams = [];
        foreach ($generatedTournamentTables as $generatedTournamentTable) {
            $winningTeams[] = $generatedTournamentTable->getTournamentTableRows()[0]->getTeamGuid();
        }

        $probabilityOfWinByTeam = [];
        foreach (array_count_values($winningTeams) as $teamGuid => $winsCount) {
            $probabilityOfWinByTeam[$teamGuid] = floor(($winsCount / self::SIMULATION_ITERATIONS_COUNT) * 100);
        }

        return new ChampionshipChancesResult($probabilityOfWinByTeam);
    }
}
