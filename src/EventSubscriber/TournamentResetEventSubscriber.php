<?php

namespace App\EventSubscriber;

use App\Event\TournamentResetEvent;
use App\Repository\TournamentRepository;
use App\Service\CacheManager\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TournamentResetEventSubscriber implements EventSubscriberInterface
{
    private TournamentRepository $tournamentRepository;
    private CacheManager $cacheManager;

    public function __construct(TournamentRepository $tournamentRepository, CacheManager $cacheManager)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->cacheManager = $cacheManager;
    }

    public function onTournamentReset(TournamentResetEvent $event): void
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => $event->getTournamentGuid()]);
        if (null === $tournament) {
            return;
        }

        $this->cacheManager->clearAllCacheForTournament($tournament);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TournamentResetEvent::EVENT_KEY => 'onTournamentReset'
        ];
    }

}
