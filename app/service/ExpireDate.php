<?php

namespace App\service;

use Carbon\Carbon;

trait ExpireDate
{
    // This Trait Get Expire Date
    public function getExpireDate($period)
    {
        $startDate = Carbon::now();

        switch ($period) {
            case 'monthly':
                return $startDate->addMonth()->format('Y-m-d');
                
            case 'quarterly':
                return $startDate->addMonths(3)->format('Y-m-d');
                
            case 'semi-annual':
                return $startDate->addMonths(6)->format('Y-m-d');
                
            case 'yearly':
                return $startDate->addYear()->format('Y-m-d');
                
            default:
                throw new \InvalidArgumentException("Invalid period specified.");
        }
    }
}
