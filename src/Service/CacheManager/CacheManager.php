<?php

namespace App\Service\CacheManager;

use App\Entity\Tournament;
use App\Service\Tournament\ChampionshipChancesPredictor\ChampionshipChancesProvider;
use App\Service\Tournament\TournamentTableProvider\TournamentTableProvider;
use Symfony\Contracts\Cache\CacheInterface;

class CacheManager
{
    private CacheInterface $cachePool;

    public function __construct(CacheInterface $defaultPool)
    {
        $this->cachePool = $defaultPool;
    }

    public function clearAllCacheForTournament(Tournament $tournament): void
    {
        $this->cachePool->delete(TournamentTableProvider::getTournamentTableCacheKey($tournament->getGuid()));
        foreach ($tournament->getRounds() as $round) {
            $this->cachePool->delete(TournamentTableProvider::getTournamentTableForRoundCacheKey($tournament->getGuid(), $round->getPosition()));
            $this->cachePool->delete(
                ChampionshipChancesProvider::getCacheKeyForChampionshipChancesForTournamentAtRound(
                    $tournament->getGuid(),
                    $round->getPosition()
                )
            );
        }
    }
}
