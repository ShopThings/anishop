<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\SmsLogResource;
use App\Models\SmsLog;
use App\Services\Contracts\SmsLogServiceInterface;
use App\Support\Filter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

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
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', SmsLog::class);
        return SmsLogResource::collection($this->service->getLogs($filter));
    }
}
