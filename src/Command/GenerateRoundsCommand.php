<?php

namespace App\Command;

use App\Repository\TournamentRepository;
use App\Service\Round\ScheduleCreator\ScheduleCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRoundsCommand extends Command
{
    private ScheduleCreator $scheduleCreator;
    private TournamentRepository $tournamentRepository;

    public function __construct(ScheduleCreator $scheduleCreator, TournamentRepository $tournamentRepository)
    {
        parent::__construct();

        $this->scheduleCreator = $scheduleCreator;
        $this->tournamentRepository = $tournamentRepository;
    }

    protected function configure()
    {
        parent::configure();

        $this->setName('app:generate-rounds');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => 'e488d3d6-f179-45b2-bd75-b9081e6a76f0']);
        $this->scheduleCreator->createSchedule($tournament);

        return 0;
    }
}
