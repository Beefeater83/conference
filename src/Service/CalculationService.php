<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;

class CalculationService
{
    public function getTimeUntilConference(): array
    {
        $now = new DateTime();
        $conferenceDate = new DateTime('2025-11-19');

        if ($now > $conferenceDate) {
            return ['months' => '00', 'days' => '00'];
        }

        $interval = $now->diff($conferenceDate);

        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        $totalMonths = $years * 12 + $months;

        $formattedMonths = ($totalMonths < 10) ? '0' . $totalMonths : (string)$totalMonths;
        $formattedDays = ($days < 10) ? '0' . $days : (string)$days;

        return [
            'months' => $formattedMonths,
            'days' => $formattedDays
        ];
    }
}
