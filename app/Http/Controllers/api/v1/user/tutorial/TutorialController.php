<?php

namespace App\Http\Controllers\api\v1\user\tutorial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TutorialGroup;

class TutorialController extends Controller
{
    public function __construct(private TutorialGroup $tutorial_groups){}

    public function tutorials(){
        $tutorial_groups = $this->tutorial_groups
        ->with(['tutorials'])
        ->get();

        return response()->json([
            'tutorial_groups' => $tutorial_groups
        ]);
    }
}
