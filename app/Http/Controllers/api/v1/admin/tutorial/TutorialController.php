<?php

namespace App\Http\Controllers\api\v1\admin\tutorial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\admin\tutorial\TutorialRequest;
use App\UploadImage;

use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function __construct(private Tutorial $tutorial){}
    protected $tutorialRequest = [
        'title',
        'description',
        'tutorial_group_id'
    ];
    use UploadImage;

    public function create(TutorialRequest $request){
        // Keys
        // title, description, tutorial_group_id, video
        $tutorialRequest = $request->only($this->tutorialRequest);
        if ($request->video) {
            $video = $this->imageUpload($request, 'video', 'admin/tutorial/videos');
            $tutorialRequest['video'] = $video;
        }
        $this->tutorial
        ->create($tutorialRequest);

        return response()->json([
            'success' => 'You add data success'
        ]);
    }

    public function modify(TutorialRequest $request, $id){
        // Keys
        // title, description, tutorial_group_id, video
        $tutorial = $this->tutorial
        ->where('id', $id)
        ->first();
        $tutorialRequest = $request->only($this->tutorialRequest);
        if (!is_string($request->video)) {
            $video = $this->imageUpload($request, 'video', 'admin/tutorial/videos');
            $tutorialRequest['video'] = $video;
        }
        $this->tutorial
        ->create($tutorialRequest);

        return response()->json([
            'success' => 'You add data success'
        ]);
    }

    public function delete($id){

    }
}
