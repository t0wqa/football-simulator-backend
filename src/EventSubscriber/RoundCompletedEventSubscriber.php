<?php

namespace App\EventSubscriber;

use App\Event\RoundCompletedEvent;
use App\Repository\RoundRepository;
use App\Service\Tournament\TournamentTableProvider\TournamentTableProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RoundCompletedEventSubscriber implements EventSubscriberInterface
{
    private RoundRepository $roundRepository;
    private TournamentTableProvider $tournamentTableProvider;

    public function __construct(
        RoundRepository $roundRepository,
        TournamentTableProvider $tournamentTableProvider
    ) {
        $this->roundRepository = $roundRepository;
        $this->tournamentTableProvider = $tournamentTableProvider;
    }

    public function onRoundCompleted(RoundCompletedEvent $event): void
    {
        $round = $this->roundRepository->findOneBy(['guid' => $event->getRoundGuid()]);
        if (null === $round) {
            return;
        }

        $tournament = $round->getTournament();

        $this->tournamentTableProvider->clearTournamentTableCache($tournament);
        $this->tournamentTableProvider->clearTournamentTableForRoundCache($tournament, $round->getPosition());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RoundCompletedEvent::EVENT_KEY => 'onRoundCompleted'
        ];
    }
}
