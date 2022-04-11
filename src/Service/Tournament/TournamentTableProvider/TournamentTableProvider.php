<?php

namespace App\Service\Tournament\TournamentTableProvider;

use App\Entity\Tournament;
use App\Service\Tournament\TournamentTableBuilder\Input\TournamentTableBuilderInput;
use App\Service\Tournament\TournamentTableBuilder\Output\TournamentTable;
use App\Service\Tournament\TournamentTableBuilder\TournamentTableBuilderInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TournamentTableProvider
{
    private const TOURNAMENT_TABLE_CACHE_KEY_TEMPLATE = 'tournament_table_%s';
    private const TOURNAMENT_TABLE_CACHE_EXPIRES_AFTER_SECONDS = 3600;

    private const TOURNAMENT_TABLE_FOR_ROUND_CACHE_KEY_TEMPLATE = 'tournament_table_%s_for_round_%d';
    private const TOURNAMENT_TABLE_FOR_ROUND_EXPIRES_AFTER_SECONDS = 3600;

    private TournamentTableBuilderInterface $tournamentTableBuilder;
    private CacheInterface $cachePool;

    public function __construct(
        TournamentTableBuilderInterface $tournamentTableBuilder,
        CacheInterface $defaultPool
    ) {
        $this->tournamentTableBuilder = $tournamentTableBuilder;
        $this->cachePool = $defaultPool;
    }

    public function getTournamentTable(Tournament $tournament): TournamentTable
    {
        $cacheKey = self::getTournamentTableCacheKey($tournament->getGuid());

        return $this->cachePool->get($cacheKey, function (ItemInterface $item) use ($tournament) {
            $item->expiresAfter(self::TOURNAMENT_TABLE_CACHE_EXPIRES_AFTER_SECONDS);

            $tournamentTableBuilderInput = (new TournamentTableBuilderInput())
                ->setTournament($tournament);

           return $this->tournamentTableBuilder->buildTournamentTable($tournamentTableBuilderInput);
        });
    }

    public function getTournamentTableForRound(Tournament $tournament, int $round): TournamentTable
    {
        $cacheKey = self::getTournamentTableForRoundCacheKey($tournament->getGuid(), $round);

        return $this->cachePool->get($cacheKey, function (ItemInterface $item) use ($tournament, $round) {
            $item->expiresAfter(self::TOURNAMENT_TABLE_FOR_ROUND_EXPIRES_AFTER_SECONDS);

            $tournamentTableBuilderInput = (new TournamentTableBuilderInput())
                ->setTournament($tournament)
                ->setMaxRound($round);

            return $this->tournamentTableBuilder->buildTournamentTable($tournamentTableBuilderInput);
        });
    }

    /**
     * Stores prepared table data into cache
     */
    public function clearTournamentTableCache(Tournament $tournament): void
    {
        $this->cachePool->delete(self::getTournamentTableCacheKey($tournament->getGuid()));
    }

    /**
     * Stores prepared table data of round into cache
     */
    public function clearTournamentTableForRoundCache(Tournament $tournament, int $round): void
    {
        $this->cachePool->delete(self::getTournamentTableForRoundCacheKey($tournament->getGuid(), $round));
    }

    public static function getTournamentTableCacheKey(string $tournamentGuid): string
    {
        return md5(sprintf(self::TOURNAMENT_TABLE_CACHE_KEY_TEMPLATE, $tournamentGuid));
    }

    public static function getTournamentTableForRoundCacheKey(string $tournamentGuid, int $round): string
    {
        return md5(sprintf(self::TOURNAMENT_TABLE_FOR_ROUND_CACHE_KEY_TEMPLATE, $tournamentGuid, $round));
    }
}
