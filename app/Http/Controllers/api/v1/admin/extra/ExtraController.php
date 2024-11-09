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
    // This Is About Extra Module

    public function store(ExtraRequest $request){
        $newExtra = $request->validated();
        $extra = $this->extra->create($newExtra);
        if(!$extra){
            return response()->json(['extra.faield'=>'Extra Process Faield'],400);
        }
          return response()->json([
          'extra.created'=>'Extra Added Successfully',
          'extra'=>$extra
          ]);
    }

    public function modify(ExtraUpdateRequest $request,$id){
        $updateExtra = $request->validated();
        $extra = $this->extra->find($id);
             if (!$extra) {
             return response()->json(['error' => 'Extra not found'], status: 404);
             }
             $extra->update($updateExtra);
            return response()->json([
                'extra.updated' => 'Extra Updated Successfully',
                'extra' => $extra,
            ], status: 200);

    }
    public function delete($id){
        $extra = $this->extra->find($id);
             if (!$extra) {
             return response()->json(['error' => 'Extra not found'], status: 404);
             }
             elseif ($extra->orders()->exists()) {
             return response()->json(['error' => 'Cannot delete category with related Order'], 400);
             }
             $extra->delete();
            return response()->json([
                'extra.updated' => 'Extra Deleted Successfully',
            ], status: 200);
                 
    }

    

    
}
