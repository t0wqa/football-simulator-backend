<?php

namespace App\MessageHandler\Command;

use App\Message\Command\ResetToNewScheduleCommand;
use App\Repository\TournamentRepository;
use App\Service\Round\ScheduleCreator\ScheduleCreator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ResetToNewScheduleCommandHandler implements MessageHandlerInterface
{
    private ScheduleCreator $scheduleCreator;
    private TournamentRepository $tournamentRepository;

    public function __construct(
        ScheduleCreator $scheduleCreator,
        TournamentRepository $tournamentRepository
    ) {
        $this->scheduleCreator = $scheduleCreator;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function __invoke(ResetToNewScheduleCommand $command): void
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => $command->getTournamentGuid()]);
        if (null === $tournament) {
            return;
        }

        $this->scheduleCreator->createSchedule($tournament);
    }
}
