<?php

namespace App\Entity\Contract;

interface GameInterface
{
    public function getStatus(): string;
    public function getHomeTeamGuid(): string;
    public function getGuestTeamGuid(): string;
}
