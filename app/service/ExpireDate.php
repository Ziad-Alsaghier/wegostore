<?php

namespace App\service;

use Carbon\Carbon;

trait ExpireDate
{
    // This Trait Get Expire Date
    public function getExpireDateTime($period, $startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now();
        foreach ($period as $period_time) {
            switch ($period_time) {
                case 'monthly':
                    return $startDate->addMonth();

                case 'quarterly':
                    return $startDate->addMonths(3);

                case 'semi-annual':
                    return $startDate->addMonths(6);

                case 'yearly':
                    return $startDate->addYear();

                default:
                    throw new \InvalidArgumentException("Invalid period specified: $period_time");
            }
        }
    }
    public function package_cycle($period, $startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now();
        foreach ($period as $period_time) {
            switch ($period_time) {
                case 'monthly':
                    return 1;

                case 'quarterly':
                    return 3;

                case 'semi-annual':
                    return 6;

                case 'yearly':
                    return 'yearly';

                default:
                    throw new \InvalidArgumentException("Invalid period specified: $period_time");
            }
        }
    }
}
