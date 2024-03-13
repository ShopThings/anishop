<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\SmsLogResource;
use App\Models\User;
use App\Services\Contracts\SmsLogServiceInterface;
use App\Support\Filter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SmsLogController extends Controller
{
    /**
     * @param SmsLogServiceInterface $service
     */
    public function __construct(
        protected SmsLogServiceInterface $service
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
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return SmsLogResource::collection($this->service->getLogs($filter));
    }
}
