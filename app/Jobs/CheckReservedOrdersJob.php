<?php

namespace App\Jobs;

use App\Models\OrderDetail;
use App\Models\OrderReserve;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckReservedOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reservedOrders = OrderReserve::all();
        $reservedOrders->each(function (OrderReserve $item) {
            /**
             * @var OrderDetail $detailOrder
             */
            $detailOrder = $item->order;

            if (!$detailOrder->is_product_returned_to_stock && $item->expires_at < now()) {
                $maxReservationTime = config('market.order.max_reservation_time', 0);

                if (
                    !$detailOrder->hasAnyPaid() ||
                    $maxReservationTime <= 0 ||
                    (
                        $detailOrder->hasAnyPaid() &&
                        $item->expires_at < now()->addSeconds($maxReservationTime)
                    )
                ) {
                    /**
                     * @var OrderServiceInterface $orderService
                     */
                    $orderService = app()->get(OrderServiceInterface::class);

                    $isReturned = $orderService->rollbackReservedOrder($detailOrder->code, $item->id);

                    if (!$isReturned) {
                        Log::channel('reserve_daily')->error(
                            'Order with code {code} is not rollback!',
                            ['code' => $detailOrder->code]
                        );
                    }
                }
            }
        });
    }
}
