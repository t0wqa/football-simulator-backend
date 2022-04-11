<?php

namespace App\Helper;

class MathHelper
{
    public static function factorial(int $number): int
    {
        $result = 1;

        for ($counter = 1; $counter <= $number; $counter++) {
            $result *= $counter;
        }

        return $result;
    }
}
