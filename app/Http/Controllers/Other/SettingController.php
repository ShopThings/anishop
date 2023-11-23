<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $this->authorize('update', $setting);

//        $validated = $request->validated();
//        $model = $this->service->updateById($weightPostPrice->id, $validated);

//        if (!is_null($model)) {
//            return new WeightPostPriceResource($model);
//        } else {
//            return response()->json([
//                'type' => ResponseTypesEnum::ERROR->value,
//                'message' => 'خطا در ویرایش هزینه ارسال',
//            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
//        }
    }
}
