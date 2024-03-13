<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Models\User;
use App\Services\Contracts\ProvinceServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        return ProvinceResource::collection($this->service->getProvinces());
    }
}
