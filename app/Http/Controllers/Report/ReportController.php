<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Contracts\ReportServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * @param ReportServiceInterface $service
     */
    public function __construct(
        protected ReportServiceInterface $service
    )
    {
    }

    public function users()
    {
        $this->authorize('canReport', User::class);


    }

    public function products()
    {
        $this->authorize('canReport', User::class);


    }

    public function orders()
    {
        $this->authorize('canReport', User::class);


    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function usersQB(): JsonResponse
    {
        $this->authorize('canReport', User::class);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getUsersQueryBuilderInfo(),
        ]);
    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function productsQB(): JsonResponse
    {
        $this->authorize('canReport', User::class);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getProductsQueryBuilderInfo(),
        ]);
    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function ordersQB(): JsonResponse
    {
        $this->authorize('canReport', User::class);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getOrdersQueryBuilderInfo(),
        ]);
    }
}
