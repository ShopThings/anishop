<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Showing\FestivalShowResource;
use App\Services\Contracts\FestivalServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeFestivalController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param FestivalServiceInterface $service
     */
    public function __construct(
        protected FestivalServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        return FestivalShowResource::collection($this->service->getHomeFestivals());
    }
}
