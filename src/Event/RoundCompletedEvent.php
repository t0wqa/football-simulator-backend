<?php

namespace App\Event;

class RoundCompletedEvent
{
    public const EVENT_KEY = 'round.completed';

    private string $roundGuid;

    public function __construct(string $roundGuid)
    {
        $this->roundGuid = $roundGuid;
    }

    public function getRoundGuid(): string
    {
        return $this->roundGuid;
    }
}
