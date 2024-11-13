<?php

namespace App\service;

use Exception;

trait CheckPrice
{
   // This Trait Check about Price Cycle

    public function calculatePrice($basePrice,$billingCycle){

        switch($billingCycle){
            case 'monthly':
                $price = $basePrice;
                        break;
            case 'quarterly':
                $price = $basePrice * 3 ; // 3 month
                        break;
             case 'semi-annual':
                $price = $basePrice * 6; // 6 months
                         break;
            case 'yearly':
                $price = $basePrice * 12; // 12 months
                        break;
            default:
                throw new Exception("Invalid Billing Cycle provided");
        }
        return $price;
    }
}
