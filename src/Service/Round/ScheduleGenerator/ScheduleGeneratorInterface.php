<?php

namespace App\Service\Round\ScheduleGenerator;

use App\Entity\Tournament;
use App\Service\Round\ScheduleGenerator\Output\ScheduleGeneratorResult;

interface ScheduleGeneratorInterface
{
    public function generateSchedule(Tournament $tournament): ScheduleGeneratorResult;
}
