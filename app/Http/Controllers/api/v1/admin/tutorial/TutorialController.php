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
        'tutorial_group_id',
        'video',
        'translations'
    ];
    use UploadImage;

    public function create(TutorialRequest $request){
        // tutorial/add
        // Keys
        // title, description, tutorial_group_id, video
         $tutorialRequest = $request->only($this->tutorialRequest);
        if ($request->video) {
            $video = $this->uploadVideo($tutorialRequest['video'],  'admin/tutorial/videos');
            $tutorialRequest['video'] = $video;
        }
       $newToutorial =  $this->tutorial
        ->create($tutorialRequest);
         if (isset($tutorialRequest['translations'])) {
                        foreach ($tutorialRequest['translations'] as $translation) {
                              $newToutorial->translations()->create($translation);
                        }
                  }

        return response()->json([
            'success' => 'You add data success',
            'tutorialUrl' => url($video)
        ]);
    }

    public function modify(TutorialRequest $request, $id){
        // tutorial/update/{id}
        // Keys
        // title, description, tutorial_group_id, video
        $tutorial = $this->tutorial
        ->find($id);
        $tutorialRequest = $request->only($this->tutorialRequest);
        if (!is_string($request->video)) {
            $video = $this->imageUpload($request, 'video', 'admin/tutorial/videos');
            $tutorialRequest['video'] = $video;
            $this->deleteImage($tutorial->video);
        }
        $tutorial->update($tutorialRequest);

        return response()->json([
            'success' => 'You update data success'
        ]);
    }

    public function delete($id){
        // tutorial/delete/{id}
        $tutorial = $this->tutorial
        ->where('id', $id)
        ->first();
        $this->deleteImage($tutorial->video);
        $tutorial->delete();

        return response()->json([
            'succes' => 'You delete data success'
        ]);
    }
}
