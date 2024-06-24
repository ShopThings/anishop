<?php

namespace App\Jobs;

use App\Enums\DatabaseEnum;
use App\Models\OrderDetail;
use App\Models\OrderReserve;
use App\Services\Contracts\OrderServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CheckReservedOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var OrderServiceInterface
     */
    protected OrderServiceInterface $orderService;

    /**
     * Create a new job instance.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->orderService = app()->get(OrderServiceInterface::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->returnReservedOrdersTorStock();
        $this->returnNotReturnedOrdersToStock();
    }

    /**
     * @return void
     */
    private function returnReservedOrdersTorStock(): void
    {
        $reservedOrders = OrderReserve::all();
        $reservedOrders->each(function (OrderReserve $item) {
            /**
             * @var OrderDetail $detailOrder
             */
            $detailOrder = $item->order;

            if (
                !$detailOrder->is_product_returned_to_stock &&
                !$detailOrder->hasCompletePaid()
            ) {
                if ($item->expires_at < now()) {
                    $maxReservationTime = abs(intval(config('market.order.max_reservation_time', 0)));

                    if (
                        !$detailOrder->hasAnyPaid() ||
                        (
                            $detailOrder->hasAnyPaid() &&
                            $item->expires_at < now()->addSeconds($maxReservationTime)
                        )
                    ) {
                        $this->returnSpecificOrderToStock($detailOrder->code, $item->id);
                    }
                }
            } else { // If product does return to stock, we should remove reservation record
                $item->delete();
            }
        });
    }

    /**
     * @return void
     */
    private function returnNotReturnedOrdersToStock(): void
    {
        $orders = OrderDetail::query()
            ->withoutAnyPaidOrder()
            ->where('is_product_returned_to_stock', DatabaseEnum::DB_NO)
            ->has('reservedOrders', '=', 0)
            ->get();
        $orders->each(function ($detailOrder) {
            $this->returnSpecificOrderToStock($detailOrder->code, null);
        });
    }

    /**
     * @param string $code
     * @param int|null $reservedId
     * @return void
     */
    private function returnSpecificOrderToStock(string $code, ?int $reservedId): void
    {
        $isReturned = $this->orderService->rollbackReservedOrder($code, $reservedId);

        if (!$isReturned) {
            Log::channel('reserve_daily')->error(
                'Order with code {code} did not rollback!',
                ['code' => $code]
            );
        }
    }
}
