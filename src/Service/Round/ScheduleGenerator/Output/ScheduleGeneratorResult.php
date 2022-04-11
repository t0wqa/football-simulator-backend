<?php

namespace App\Service\Round\ScheduleGenerator\Output;

use App\Service\Round\ScheduleGenerator\DTO\Round;

class ScheduleGeneratorResult
{
    /**
     * @var Round[]
     */
    private array $rounds;

    public function __construct(array $rounds)
    {
        $this->rounds = $rounds;
    }

    public function getRounds(): array
    {
        return $this->rounds;
    }
}
