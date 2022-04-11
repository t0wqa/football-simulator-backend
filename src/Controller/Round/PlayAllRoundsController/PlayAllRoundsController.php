<?php

namespace App\Controller\Round\PlayAllRoundsController;

use App\Message\Command\PlayAllRoundsCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PlayAllRoundsController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/tournaments/{tournamentGuid}/tournament-simulation", name="play_all_rounds", methods={"POST"})
     */
    public function __invoke(string $tournamentGuid): Response
    {
        $this->messageBus->dispatch(new PlayAllRoundsCommand($tournamentGuid));

        return new JsonResponse(['success' => true]);
    }
}
