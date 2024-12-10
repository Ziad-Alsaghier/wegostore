<?php

namespace App;

trait CheckExtraIncludedTrait
{
    //

    public function check($extra,$user){
        // $getExtra = $this->extra->where('id', $extra['extra_id'])->first();
        // return $getExtra->plan_included;
        // $extra = 1;
        $user_plan = $user->plan;
              $extra_included = $user_plan->extras->where('id',$extra);
          if(count($extra_included) <= 0){
                return false;
          }else{
                return true;
          }
            # code...
    }
}
