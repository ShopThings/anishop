<?php

namespace App\Http\Controllers\Report;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Contracts\ReportServiceInterface;

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

    public function usersQB()
    {
        $this->authorize('canReport', User::class);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getUsersQueryBuilderInfo(),
        ]);
    }

    public function productsQB()
    {
        $this->authorize('canReport', User::class);


    }

    public function ordersQB()
    {
        $this->authorize('canReport', User::class);


    }
}
