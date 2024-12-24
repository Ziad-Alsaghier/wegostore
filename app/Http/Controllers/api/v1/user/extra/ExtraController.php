<?php

namespace App\Http\Controllers\api\v1\user\extra;

use App\CheckExtraIncludedTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExtraResource;
use Illuminate\Http\Request;

use App\Models\Extra;
use App\Models\Order;
use Ramsey\Collection\Tool\ValueExtractorTrait;

class ExtraController extends Controller
{
    use CheckExtraIncludedTrait;
    public function __construct(private Extra $extra, private Order $order) {}

    public function view(Request $request)
    {
            $locale = $request->query('locale', app()->getLocale());
        $user_id = $request->user()->id;
        // extra
        $extra = $this->extra
        ->withLocale($locale)
            ->get();
            $extra = ExtraResource::collection($extra);

        $orders_items = $this->order
            ->whereNotNull('extra_id')
            ->whereNull('expire_date')
            ->where('user_id', $user_id)
            ->whereHas('payment', function ($query) {
                $query->where('status', 'approved');
            })
            ->orWhereNotNull('extra_id')
            ->where('expire_date', '>=', date('Y-m-d'))
            ->whereHas('payment', function ($query) {
                $query->where('status', 'approved');
            })
            ->where('user_id', $user_id)
            ->get();
        $orders = $orders_items->pluck('extra_id');
        foreach ($extra as $item) {
            if ($orders->contains($item->id)) {
                $item->order_status = $orders_items
                    ->where('extra_id', $item->id)
                    ->first()
                    ->order_status;
                $item->my_extra = true;
            } else {
                $item->my_extra = false;
                $item->order_status = null;
            }
        }
        

        return response()->json([
            'extras' => $extra
        ]);
    }

    public function check_included(Extra $extra = Null, Request $request)
    {
        URL : http://wegostore.test/user/v1/extra/check/included/{id}
        
    }
}
