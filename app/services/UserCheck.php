<?php

namespace App\services;

use Illuminate\Http\Exceptions\HttpResponseException;

trait UserCheck
{
  //

  public function checkUserPlan($user)
  {
    $user_plan_id = $user->plan_id;
    $user_plan = $this->plan->where('id', $user_plan_id)->first();
    if (empty($user_plan)) {
      return false;
    }
    return true;
  }

  public function checkPlanUsed($user, $plan_id)
  {
    $userPlan_id = $user->plan_id;
    foreach ($plan_id as $id) {
      if ($userPlan_id == $id) {
        return true;
      }
      return  false;
    }
  }
}
