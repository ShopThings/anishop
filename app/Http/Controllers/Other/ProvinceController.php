<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use App\Services\Contracts\ProvinceServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProvinceController extends Controller
{
    /**
     * @param ProvinceServiceInterface $service
     */
    public function __construct(
        protected ProvinceServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProvinceResource::collection($this->service->getProvinces());
    }

    /**
     * @param Province $province
     * @return ProvinceResource
     */
    public function show(Province $province): ProvinceResource
    {
        return new ProvinceResource($province);
    }
}
