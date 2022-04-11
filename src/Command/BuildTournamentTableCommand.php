<?php

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Service\Tournament\TournamentTableBuilder\Input\TournamentTableBuilderInput;
use App\Service\Tournament\TournamentTableBuilder\TournamentTableBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildTournamentTableCommand extends Command
{
    private TournamentRepository $tournamentRepository;
    private TournamentTableBuilderInterface $tournamentTableBuilder;

    public function __construct(TournamentRepository $tournamentRepository, TournamentTableBuilderInterface $tournamentTableBuilder)
    {
        parent::__construct();

        $this->tournamentRepository = $tournamentRepository;
        $this->tournamentTableBuilder = $tournamentTableBuilder;
    }

    protected function configure()
    {
        parent::configure();

        $this->setName('app:build-table');
        $this->addOption('maxRound', null, InputOption::VALUE_OPTIONAL, 'Max Round');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => 'e488d3d6-f179-45b2-bd75-b9081e6a76f0']);
        $table = $this->tournamentTableBuilder->buildTournamentTable(
            (new TournamentTableBuilderInput())
                ->setTournament($tournament)
                ->setMaxRound($input->getOption('maxRound'))
        );

        foreach ($table->getTournamentTableRows() as $tournamentTableRow) {
            print_r([
                'guid' => $tournamentTableRow->getTeamGuid(),
                'title' => $tournamentTableRow->getTeamTitle(),
                'gamesPlayedCount' => $tournamentTableRow->getGamesPlayedCount(),
                'winsCount' => $tournamentTableRow->getWinsCount(),
                'defeatsCount' => $tournamentTableRow->getDefeatsCount(),
                'drawsCount' => $tournamentTableRow->getDrawsCount(),
                'goalsScored' => $tournamentTableRow->getGoalsScored(),
                'goalsMissed' => $tournamentTableRow->getGoalsMissed(),
                'goalsDifference' => $tournamentTableRow->getGoalsDifference(),
                'score' => $tournamentTableRow->getScore()
            ]);
        }

        return 0;
    }
}
