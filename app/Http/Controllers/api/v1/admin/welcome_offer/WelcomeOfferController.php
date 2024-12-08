<?php

namespace App\Http\Controllers\api\v1\admin\welcome_offer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UploadImage;
use App\Http\Requests\api\v1\admin\welcome_offer\WelcomeOfferRequest;

use App\Models\WelcomeOffer;

class WelcomeOfferController extends Controller
{
    public function __construct(private WelcomeOffer $offer){}
    protected $offerRequest = [
        'plan_id',
        'duration',
        'price',
        'status',
    ];
    use UploadImage;

    public function view(){
        // welcome_offer
        $offer = $this->offer
        ->with('plan')
        ->orderByDesc('id')
        ->first();

        return response()->json([
            'offer' => $offer
        ]);
    }

    public function create(WelcomeOfferRequest $request){
        // welcome_offer/add
        // Keys
        // plan_id, duration => [quarterly,semi_annual,monthly,yearly], 
        // price, status, ar_image, en_image
        $offer = $this->offer
        ->first();
        if (!empty($offer)) {
            return response()->json([
                'faild' => 'You must delete old offer first'
            ], 400);
        }
        $offerRequest = $request->only($this->offerRequest);
        if ($request->ar_image) {
            $ar_image = $this->imageUpload($request, 'ar_image', 'admin/welcome_offer/image');
            $offerRequest['ar_image'] = $ar_image;
        }
        if ($request->en_image) {
            $en_image = $this->imageUpload($request, 'en_image', 'admin/welcome_offer/image');
            $offerRequest['en_image'] = $en_image;
        }
        $this->offer
        ->create($offerRequest);

        return response()->json([
            'success' => 'You add data success'
        ]);
    }

    public function modify(WelcomeOfferRequest $request, $id){
        // welcome_offer/update/{id}
        // Keys
        // plan_id, duration => [quarterly,semi_annual,monthly,yearly], 
        // price, status, ar_image, en_image
        $offer = $this->offer
        ->where('id', $id)
        ->first();
        $offerRequest = $request->only($this->offerRequest);
        if ($request->ar_image) {
            $ar_image = $this->imageUpdate($request, $offer, 'ar_image', 'admin/welcome_offer/image');
            $offerRequest['ar_image'] = $ar_image;
        }
        if ($request->en_image) {
            $en_image = $this->imageUpdate($request, $offer, 'en_image', 'admin/welcome_offer/image');
            $offerRequest['en_image'] = $en_image;
        }
        $offer
        ->update($offerRequest);

        return response()->json([
            'success' => 'You update data success'
        ]);
    }

    public function delete($id){
        // welcome_offer/delete/{id}
        $offer = $this->offer
        ->where('id', $id)
        ->first();
        $this->deleteImage($offer->ar_image);
        $this->deleteImage($offer->en_image);
        $offer->delete();

        return response()->json([
            'success' => 'You delete data success'
        ]);
    }
}
