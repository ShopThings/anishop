<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Home\SendMethodResource as HomeSendMethodResource;
use App\Services\Contracts\SendMethodServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeSendMethodController extends Controller
{

    /**
     * @param SendMethodServiceInterface $service
     */
    public function __construct(
        protected readonly SendMethodServiceInterface $service
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
        return HomeSendMethodResource::collection($this->service->getHomeMethods());
    }
}
