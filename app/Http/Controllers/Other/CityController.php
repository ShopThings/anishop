<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Province;
use App\Services\Contracts\CityServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CityController extends Controller
{
    /**
     * @param CityServiceInterface $service
     */
    public function __construct(
        protected CityServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Province $province
     * @return AnonymousResourceCollection
     */
    public function index(Province $province): AnonymousResourceCollection
    {
        return CityResource::collection($this->service->getCities($province->id));
    }

    /**
     * @param Province $province
     * @param City $city
     * @return CityResource|JsonResponse
     */
    public function show(Province $province, City $city): CityResource|JsonResponse
    {
        if ($city->province_id !== $province->id) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'شهر انتخاب شده برای این استان نمی‌باشد.',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        }

        return new CityResource($city);
    }
}
