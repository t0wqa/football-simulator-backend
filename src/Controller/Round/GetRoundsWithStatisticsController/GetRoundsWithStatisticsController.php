<?php

namespace App\Controller\Round\GetRoundsWithStatisticsController;

use App\Controller\ControllerTrait;
use App\Repository\TournamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetRoundsWithStatisticsController extends AbstractController
{
    use ControllerTrait;

    private OutputBuilder $outputBuilder;
    private TournamentRepository $tournamentRepository;
    private SerializerInterface $serializer;

    public function __construct(
        OutputBuilder $outputBuilder,
        TournamentRepository $tournamentRepository,
        SerializerInterface $serializer
    ) {
        $this->outputBuilder = $outputBuilder;
        $this->tournamentRepository = $tournamentRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/tournaments/{tournamentGuid}/rounds/statistics", name="get_rounds_with_statistics", methods={"GET"})
     */
    public function __invoke(string $tournamentGuid): Response
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => $tournamentGuid]);
        if (null === $tournament) {
            throw new NotFoundHttpException('Tournament not found');
        }

        $output = $this->outputBuilder->build($tournament);

        return $this->createSuccessfulResponseForJsonString(
            $this->serializer->serialize($output, 'json')
        );
    }
}
