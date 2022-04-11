<?php

namespace App\MessageHandler\Command;

use App\Message\Command\PlayAllRoundsCommand;
use App\Repository\TournamentRepository;
use App\Service\Tournament\TournamentSimulator\TournamentSimulator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PlayAllRoundsCommandHandler implements MessageHandlerInterface
{
    private TournamentSimulator $tournamentSimulator;
    private TournamentRepository $tournamentRepository;

    public function __construct(TournamentSimulator $tournamentSimulator, TournamentRepository $tournamentRepository)
    {
        $this->tournamentSimulator = $tournamentSimulator;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function __invoke(PlayAllRoundsCommand $command): void
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => $command->getTournamentGuid()]);
        if (null === $tournament) {
            return;
        }

        $this->tournamentSimulator->simulate($tournament);
    }
}
