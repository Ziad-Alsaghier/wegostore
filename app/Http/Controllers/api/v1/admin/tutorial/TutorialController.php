<?php

namespace App\Http\Controllers\api\v1\admin\tutorial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\admin\tutorial\TutorialRequest;

use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function __construct(private Tutorial $tutorial){}
    protected $tutorialRequest = [
        'title',
        'description',
        'tutorial_group_id'
    ];

    public function view(){
        $tutorials = $this->tutorial
        ->get();

        return response()->json([
            'tutorials' => $tutorials
        ]);
    }

    public function create(TutorialRequest $request){
        // Keys
        // title, description, tutorial_group_id, video
    }

    public function modify(TutorialRequest $request, $id){
        // Keys
        // title, description, tutorial_group_id, video
    }

    public function delete($id){

    }
}
