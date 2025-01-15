<?php

namespace App\Http\Controllers\api\v1\admin\extra;

use App\CheckExtraIncludedTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\admin\extra\ExtraRequest;
use App\Http\Requests\api\v1\admin\extra\ExtraUpdateRequest;
use App\Http\Resources\ExtraResource;
use App\Models\Extra;
use App\Models\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExtraController extends Controller
{
    public function __construct(
        private Extra $extra,
        private Plan $plan
    ) {}
    use CheckExtraIncludedTrait;
    protected $extraRequest = [
        'name',
        'price',
        'description',
        'status',
        'yearly',
        'setup_fees',
        'monthly',
        'quarterly',
        'included',
        'semi_annual',
        'discount_monthly',
        'discount_quarterly',
        'discount_semi_annual',
        'discount_yearly',
        'translations',
    ];

    // This Is About Extra Module
    public function view(Request $request)
    {

        try {
            $locale = $request->query('locale', app()->getLocale());
            $extra = $this->extra->withLocale($locale)->get();
            $extraLocal = ExtraResource::collection($extra);

            return response()->json([
                'extra.view' => 'Data Extra returened Successfully',
                'extra' => $extraLocal,
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'extra.error' => 'Something Wrong in Extra',
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function store(ExtraRequest $request)
    {
        // Keys
        // name, price, description, status, yearly, setup_fees, monthly, 
        // quarterly, semi_annual, discount_monthly, discount_quarterly, 
        // discount_semi_annual, discount_yearly
        $newExtra = $request->validated();

        $included = $newExtra['included'];

        $extra = $this->extra->create($newExtra);
        if ($included == true) {
            $plans = $newExtra['plans']  ?? false;
            if ($plans) {
                $extra->plan_included()->syncWithoutDetaching($plans);
            } else {
                $allPlans = $this->plan->get();
                $plans = $allPlans->pluck('id');
            }
            $extra->plan_included()->syncWithoutDetaching($plans);
        }
        // Add translations
        if (isset($newExtra['translations'])) {
            foreach ($newExtra['translations'] as $translation) {
                $extra->translations()->create($translation);
            }
        }
        if (!$extra) {
            return response()->json(['extra.faield' => 'Extra Process Faield'], 400);
        }
        return response()->json([
            'extra.create' => 'Extra Added Successfully',
            'extra' => $extra
        ]);
    }

    public function modify(ExtraUpdateRequest $request, $id)
    {
        // extra/update/{id}
        // Keys
        // name, price, description, status, yearly, setup_fees, monthly, 
        // quarterly, semi_annual, discount_monthly, discount_quarterly, 
        // discount_semi_annual, discount_yearly
        $updateExtra = $request->validated();
        $extra = $this->extra->find($id);
        if (!$extra) {
            return response()->json(['error' => 'Extra not found'], status: 404);
        }
        if(isset($request->plans)){
            $plans = $request->plans;
            $extra->plan_included()->syncWithoutDetaching($plans); 
        }
        $extra->update($updateExtra);
       if (isset($updateExtra['translations'])) {
        foreach ($updateExtra['translations'] as $translation) {
            $extra->translations()->updateOrCreate(
                ['value' => $translation['value']], // Match condition
                $translation // Data to update or insert
            );
        }
    }
      

        return response()->json([
            'extra.update' => 'Extra Updated Successfully',
            'extra' => $extra,
            'translations' => $extra->translations,
        ], status: 200);
    }

    public function delete($id)
    {
        $extra = $this->extra->find($id);
        if (!$extra) {
            return response()->json(['error' => 'Extra not found'], status: 404);
        }
        $extra->delete();
        return response()->json([
            'extra.delete' => 'Extra Deleted Successfully',
        ], status: 200);
    }

    public function included(Extra $extra, Request $request)
    {
        URL: http: //wegostore.test/admin/v1/extra/included/{id}
        $plans = $request->plans;
        $extra->plan_included()->sync($plans);
        try {

            if ($extra) {
                return response()->json([
                    'extra' => "Extra : $extra->name Included Successfully To Extra",
                ]);
            }
        } catch (Throwable $th) {
            throw new HttpResponseException(response()->json([
                'message' => 'Something Wrong In Make Included Plan or Not Found This Extra'
            ], 400));
        }
    }
    
}
