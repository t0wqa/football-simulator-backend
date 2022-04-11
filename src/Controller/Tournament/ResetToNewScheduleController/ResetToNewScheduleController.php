<?php

namespace App\Controller\Tournament\ResetToNewScheduleController;

use App\Message\Command\ResetToNewScheduleCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetToNewScheduleController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/tournaments/{tournamentGuid}/reset", name="reset_tournament", methods={"POST"})
     */
    public function __invoke(string $tournamentGuid): Response
    {
        $this->messageBus->dispatch(new ResetToNewScheduleCommand($tournamentGuid));

        return new JsonResponse(['success' => true]);
    }
}
