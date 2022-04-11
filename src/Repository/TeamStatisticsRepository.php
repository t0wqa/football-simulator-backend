<?php

namespace App\Repository;

use App\Entity\TeamStatistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamStatistics::class);
    }

    /**
     * @return TeamStatistics[]
     */
    public function findAllByTournamentGuid(string $tournamentGuid): array
    {
        $qb = $this->createQueryBuilder('teamStatistics');

        return $qb
            ->select('teamStatistics')
            ->innerJoin('teamStatistics.team', 'team')
            ->innerJoin('team.tournaments', 'tournamentTeam')
            ->andWhere($qb->expr()->eq('tournamentTeam.tournament', ':tournamentGuid'))
            ->setParameter('tournamentGuid', $tournamentGuid)
            ->getQuery()
            ->getResult();
    }
}
