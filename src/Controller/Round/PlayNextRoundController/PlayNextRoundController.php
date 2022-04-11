<?php

namespace App\Controller\Round\PlayNextRoundController;

use App\Message\Command\PlayNextRoundCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PlayNextRoundController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/tournaments/{tournamentGuid}/round-simulation", name="play_next_round", methods={"POST"})
     */
    public function __invoke(string $tournamentGuid): Response
    {
        $this->messageBus->dispatch(new PlayNextRoundCommand($tournamentGuid));

        return new JsonResponse(['success' => true]);
    }
}
