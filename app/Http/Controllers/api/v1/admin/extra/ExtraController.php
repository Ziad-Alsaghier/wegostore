<?php

namespace App\Http\Controllers\api\v1\admin\extra;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\extra\ExtraRequest;
use App\Http\Requests\api\v1\admin\extra\ExtraUpdateRequest;
use App\Models\Extra;
use Illuminate\Http\Request;

class ExtraController extends Controller
{
    public function __construct(private Extra $extra){}
    protected $extraRequest = [
        'name',
        'price',
        'description',
        'status',
        'yearly',
        'setup_fees',
        'monthly',
        'quarterly',
        'semi-annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
    ];

    // This Is About Extra Module
        public function view(){
            try {
                $extra = $this->extra->all();
            return response()->json([
                'extra.view'=>'Data Extra returened Successfully',
                'extra'=>$extra,
            ]);
            } catch (\Throwable $th) {
            return response()->json([
                'extra.error'=>'Something Wrong in Extra',
                'message'=>$th->getMessage(),
            ]);
            }
        }

    public function store(ExtraRequest $request){
       // Keys
       // name, price, description, status, yearly, setup_fees, monthly, 
       // quarterly, semi-annual, discount_monthly, discount_quarterly, 
       // discount_semi_annual, discount_yearly
        $newExtra = $request->only($this->extraRequest);
        
        $extra = $this->extra->create($newExtra);
        
        if(!$extra){
            return response()->json(['extra.faield'=>'Extra Process Faield'],400);
        }
          return response()->json([
          'extra.create'=>'Extra Added Successfully',
          'extra'=>$extra
          ]);
    }

    public function modify(ExtraUpdateRequest $request,$id){
        // Keys
        // name, price, description, status, yearly, setup_fees, monthly, 
        // quarterly, semi-annual, discount_monthly, discount_quarterly, 
        // discount_semi_annual, discount_yearly
        $updateExtra = $request->validated();
        $extra = $this->extra->find($id);
             if (!$extra) {
             return response()->json(['error' => 'Extra not found'], status: 404);
             }
             $extra->update($updateExtra);
            return response()->json([
                'extra.update' => 'Extra Updated Successfully',
                'extra' => $extra,
            ], status: 200);

    }
    
    public function delete($id){
        $extra = $this->extra->find($id);
             if (!$extra) {
             return response()->json(['error' => 'Extra not found'], status: 404);
             }
             $extra->delete();
            return response()->json([
                'extra.delete' => 'Extra Deleted Successfully',
            ], status: 200);
    }

    

    
}
