<?php

namespace App\Http\Controllers\api\v1\admin\tutorial_group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UploadImage;

use App\Models\TutorialGroup;

class TutorialGroupController extends Controller
{
    public function __construct(private TutorialGroup $tutorial_group){}
    protected $groupRequest = [
        'name'
    ];
    use UploadImage;

    public function view(){
        $tutorial_group = $this->tutorial_group
        ->with('tutorials')
        ->get();

        return response()->json([
            'tutorial_group' => $tutorial_group
        ]);
    }

    public function create(Request $request){
        // Keys
        // name
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $groupRequest = $request->only($this->groupRequest);
        $this->tutorial_group
        ->create($groupRequest);

        return response()->json([
            'success' => 'You add data success'
        ]);
    }

    public function modify(Request $request, $id){
        // Keys
        // name
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) { // if Validate Make Error Return Message Error
            return response()->json([
                'error' => $validator->errors(),
            ],400);
        }
        $groupRequest = $request->only($this->groupRequest);
        $this->tutorial_group
        ->where('id', $id)
        ->update($groupRequest);

        return response()->json([
            'success' => 'You update data success'
        ]);
    }

    public function delete($id){
        $tutorial_group = $this->tutorial_group
        ->where('id', $id)
        ->with('tutorials')
        ->first();
        if (!empty($tutorial_group->tutorials)) {
            foreach ($tutorial_group->tutorials as $key => $item) {
                $this->deleteImage($item->video);
            }
        }
        $tutorial_group->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }
}
