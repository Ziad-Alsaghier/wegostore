<?php

namespace App\service;

use Illuminate\Http\Exceptions\HttpResponseException;

trait UserCheck
{
    //

    public function checkPlan($user){
          $user_plan_id = $user->plan_id;
          $user_plan = $this->plan->where('id',$user_plan_id)->first();
          if(empty($user_plan)){
            return false;
          }
        return true;
    }
}
