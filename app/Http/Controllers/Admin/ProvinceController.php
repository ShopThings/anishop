<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
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
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return ProductResource::collection($this->service->getProvinces());
    }
}
