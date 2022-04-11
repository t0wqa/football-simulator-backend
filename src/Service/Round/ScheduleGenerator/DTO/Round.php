<?php

namespace App\Service\Round\ScheduleGenerator\DTO;

class Round
{
    /**
     * @var Game[]
     */
    private array $games;

    public function __construct(array $games)
    {
        $this->games = $games;
    }

    public function getGames(): array
    {
        return $this->games;
    }
}
