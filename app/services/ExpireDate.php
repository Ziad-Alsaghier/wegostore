<?php

namespace App\services;

use Carbon\Carbon;

trait ExpireDate
{
    // This Trait Get Expire Date
    public function getExpireDateTime($period, $startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now();
         $period;
        foreach ($period as $period_time) {
            switch ($period_time) {
                case 'monthly':
                    return $date = $startDate->addMonth();

                case 'quarterly':
                    return $startDate->addMonths(3);

                case 'semi_annual':
                    return $startDate->addMonths(6);

                case 'yearly':
                    return $startDate->addYear();

                default:
                    throw new \InvalidArgumentException("Invalid period specified: $period_time");
            }

        }
    }
    public function getOrderDateExpire($period, $startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now();
         $period;
            switch ($period) {
                case 'monthly':
                    return $date = $startDate->addMonth();

                case 'quarterly':
                    return $startDate->addMonths(3);

                case 'semi_annual':
                    return $startDate->addMonths(6);

                case 'yearly':
                    return $startDate->addYear();

                default:
                    throw new \InvalidArgumentException("Invalid period specified: $period");

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

                case 'semi_annual':
                    return 6;

                case 'yearly':
                    return 'yearly';

                default:
                    throw new \InvalidArgumentException("Invalid period specified: $period_time");
            }
        }
    }
}
