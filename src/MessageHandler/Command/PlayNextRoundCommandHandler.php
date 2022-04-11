<?php

namespace App\MessageHandler\Command;

use App\Message\Command\PlayNextRoundCommand;
use App\Repository\TournamentRepository;
use App\Service\Round\RoundSimulator\RoundSimulatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PlayNextRoundCommandHandler implements MessageHandlerInterface
{
    private RoundSimulatorInterface $roundSimulator;
    private TournamentRepository $tournamentRepository;

    public function __construct(RoundSimulatorInterface $roundSimulator, TournamentRepository $tournamentRepository)
    {
        $this->roundSimulator = $roundSimulator;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function __invoke(PlayNextRoundCommand $command)
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => $command->getTournamentGuid()]);
        if (null === $tournament) {
            return;
        }

        $currentRound = $tournament->getCurrentRound();
        if (null === $currentRound) {
            return;
        }

        $this->roundSimulator->simulate($currentRound);
    }
}
