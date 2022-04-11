<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ControllerTrait
{
    public function createSuccessfulResponseForJsonString(string $jsonString): JsonResponse
    {
        return new JsonResponse(
            $jsonString,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
