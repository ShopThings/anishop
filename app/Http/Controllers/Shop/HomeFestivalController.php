<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Showing\FestivalShowResource;
use App\Services\Contracts\FestivalServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
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
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return FestivalShowResource::collection($this->service->getHomeFestivals());
    }
}
