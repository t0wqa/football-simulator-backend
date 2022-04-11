<?php

namespace App\Controller\Tournament\GetTournamentTableController;

use App\Controller\ControllerTrait;
use App\Repository\TournamentRepository;
use App\Service\Tournament\TournamentTableProvider\TournamentTableProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetTournamentTableController extends AbstractController
{
    use ControllerTrait;

    private TournamentTableProvider $tournamentTableProvider;
    private TournamentRepository $tournamentRepository;
    private SerializerInterface $serializer;

    public function __construct(
        TournamentTableProvider $tournamentTableProvider,
        TournamentRepository $tournamentRepository,
        SerializerInterface $serializer
    ) {
        $this->tournamentTableProvider = $tournamentTableProvider;
        $this->tournamentRepository = $tournamentRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/tournaments/{guid}/table", name="get_tournament_table", methods={"GET"})
     */
    public function __invoke(string $guid): Response
    {
        $tournament = $this->tournamentRepository->findOneBy(['guid' => $guid]);
        if (null === $tournament) {
            throw new NotFoundHttpException('Tournament not found');
        }

        return $this->createSuccessfulResponseForJsonString(
            $this->serializer->serialize($this->tournamentTableProvider->getTournamentTable($tournament), 'json')
        );
    }
}
