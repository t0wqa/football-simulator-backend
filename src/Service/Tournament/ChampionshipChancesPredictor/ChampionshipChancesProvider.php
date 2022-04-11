<?php

namespace App\Service\Tournament\ChampionshipChancesPredictor;

use App\Entity\Tournament;
use App\Service\Tournament\ChampionshipChancesPredictor\Output\ChampionshipChancesResult;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ChampionshipChancesProvider
{
    private const CHAMPIONSHIP_CHANCES_FOR_TOURNAMENT_AT_ROUND_CACHE_KEY_TEMPLATE = 'championship_chances_for_tournament_%s_at_round_%s';
    private const CHAMPIONSHIP_CHANCES_FOR_TOURNAMENT_AT_ROUND_EXPIRES_AFTER_SECONDS = 3600;

    private CacheInterface $cachePool;
    private ChampionshipChancesPredictorInterface $championshipChancesPredictor;

    public function __construct(CacheInterface $defaultPool, ChampionshipChancesPredictorInterface $championshipChancesPredictor)
    {
        $this->cachePool = $defaultPool;
        $this->championshipChancesPredictor = $championshipChancesPredictor;
    }

    public function getChances(Tournament $tournament, int $atRound): ChampionshipChancesResult
    {
        $cacheKey = self::getCacheKeyForChampionshipChancesForTournamentAtRound($tournament->getGuid(), $atRound);
        return $this->cachePool->get($cacheKey, function (ItemInterface $item) use ($tournament, $atRound) {
            $item->expiresAfter(self::CHAMPIONSHIP_CHANCES_FOR_TOURNAMENT_AT_ROUND_EXPIRES_AFTER_SECONDS);

            return $this->championshipChancesPredictor->predictChances($tournament, $atRound);
        });
    }

    public static function getCacheKeyForChampionshipChancesForTournamentAtRound(string $tournamentGuid, int $atRound): string
    {
        return md5(sprintf(self::CHAMPIONSHIP_CHANCES_FOR_TOURNAMENT_AT_ROUND_CACHE_KEY_TEMPLATE, $tournamentGuid, $atRound));
    }
}
