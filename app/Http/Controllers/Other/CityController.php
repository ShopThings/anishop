<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\Province;
use App\Services\Contracts\CityServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
}
