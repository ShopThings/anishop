<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Enums\Gates\RolesEnum;
use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Settings\SettingsEnum;
use App\Events\OrderPlacedEvent;
use App\Events\OrderStatusChangedEvent;
use App\Exceptions\InvalidCartNameException;
use App\Models\User;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\Contracts\OrderBadgeRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentMethodRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Repositories\Contracts\SendMethodRepositoryInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Cart\Cart;
use App\Support\Filter;
use App\Support\Helper\OrderHelper;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Snortlin\NanoId\NanoIdInterface;

class OrderService extends Service implements OrderServiceInterface
{
    public function __construct(
        protected OrderRepositoryInterface      $repository,
        protected OrderBadgeRepositoryInterface $badgeRepository,
        protected ProvinceRepositoryInterface   $provinceRepository,
        protected CityRepositoryInterface       $cityRepository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getByCode(string $code): ?Model
    {
        $where = new WhereBuilder();
        $where->whereEqual('code', $code);

        return $this->repository->findWhere(where: $where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserOrderByCode(int $userId, string $code): ?Model
    {
        if (empty($userId) || empty(trim($code))) return null;

        $where = new WhereBuilder();
        $where
            ->whereEqual('user_id', $userId)
            ->whereEqual('code', $code);

        return $this->repository->findWhere(where: $where->build());
    }

    /**
     * @inheritDoc
     */
    public function getOrders(?int $userId = null, Filter $filter = null): Collection|LengthAwarePaginator
    {
        return $this->repository->getOrdersSearchFilterPaginated(userId: $userId, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function getOrdersCount(): int
    {
        return $this->repository->count();
    }

    /**
     * @inheritDoc
     */
    public function getOrdersCountWithBadges(): Collection
    {
        return $this->repository->getOrdersCountWithBadges();
    }

    /**
     * @inheritDoc
     */
    public function getPaymentStatuses(): array
    {
        return PaymentStatusesEnum::translationArray();
    }

    /**
     * @inheritDoc
     */
    public function getSendStatuses(): Collection
    {
        $where = new WhereBuilder('order_badges');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->badgeRepository->all(
            where: $where->build(), order: ['id' => 'asc']
        );
    }

    /**
     * @inheritDoc
     */
    public function getUserOrdersCount($userId): int
    {
        $where = new WhereBuilder('order_details');
        $where->whereEqual('user_id', $userId);

        return $this->repository->count($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserLatestOrders($userId, int $limit): Collection
    {
        $filter = new Filter();
        $filter->reset()
            ->setLimit($limit)
            ->setOrder([
                'ordered_at' => 'desc',
                'id' => 'desc',
            ]);

        return collect($this->repository->getOrdersSearchFilterPaginated(
            userId: $userId,
            filter: $filter
        )->items());
    }

    /**
     * @inheritDoc
     */
    public function getUserUnpaidOrderPayments(User $user): Collection
    {
        $orderPayments = $user->reservedOrders()
            ->withWhereHas('order', function ($q) {
                $q->with('orders')->withPendingOrder();
            })->get();

        return $orderPayments->pluck('order');
    }

    /**
     * @inheritDoc
     */
    public function placeOrder(User $user, array $orderInfo, Cart $cart): ?Model
    {
        $result = DB::transaction(function () use ($user, $orderInfo, $cart) {
            // add order detail to detail table and check it
            $addResult = $this->addOrderDetail($user, $orderInfo, $cart);
            if (is_null($addResult)) {
                DB::rollBack();
                return null;
            }

            $orderDetail = $addResult['order_detail'];
            $paymentChunksCount = $addResult['payment_chunks_count'];

            // insert all items in order items table and check it
            $itemsInserted = $this->addOrderItems($orderDetail->id, $cart);
            if (!$itemsInserted) {
                DB::rollBack();
                return null;
            }

            // make it as a reserved order and check it
            $reserved = $this->reserveOrder($orderDetail->id, $paymentChunksCount);
            if (!$reserved) {
                DB::rollBack();
                return null;
            }

            return $orderDetail;
        });

        if (!is_null($result)) {
            OrderPlacedEvent::dispatch($user, $result->code);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function rollbackReservedOrder(string $code, ?int $reservedId): bool
    {
        return $this->repository->rollbackReservedOrder($code, $reservedId);
    }

    /**
     * @inheritDoc
     */
    public function createGatewayPayment(array $attributes): ?Model
    {
        $attrs = [
            'order_id' => $attributes['order_id'],
            'message' => $attributes['message'] ?? '',
            'transaction' => $attributes['transaction'] ?? null,
            'gateway_type' => $attributes['gateway_type'] ?? null,
            'meta' => $attributes['meta'] ?? null,
        ];

        return $this->repository->createGatewayPayment($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateByCode($code, array $attributes, bool $silence = false): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['province'])) {
            $updateAttributes['province'] = $this->provinceRepository->find($attributes['province'])?->name;
        }
        if (isset($attributes['city'])) {
            if (isset($attributes['province'])) {
                $where = new WhereBuilder('cities');
                $where
                    ->whereEqual('province_id', $attributes['province'])
                    ->whereEqual('id', $attributes['city']);

                $updateAttributes['city'] = $this->cityRepository->findWhere(
                    where: $where->build(),
                    columns: ['name']
                )?->name;
            } else {
                $updateAttributes['city'] = $this->cityRepository->find($attributes['city'])?->name;
            }
        }
        if (isset($attributes['address'])) {
            $updateAttributes['address'] = $attributes['address'];
        }
        if (isset($attributes['postal_code'])) {
            $updateAttributes['postal_code'] = $attributes['postal_code'];
        }
        if (isset($attributes['receiver_name'])) {
            $updateAttributes['receiver_name'] = $attributes['receiver_name'];
        }
        if (isset($attributes['receiver_mobile'])) {
            $updateAttributes['receiver_mobile'] = $attributes['receiver_mobile'];
        }
        if (isset($attributes['send_status'])) {
            $status = $this->badgeRepository->find($attributes['send_status']);
            if ($status instanceof Model) {
                $updateAttributes['send_status_is_starting_badge'] = $status->is_starting_badge;
                $updateAttributes['send_status_is_end_badge'] = $status->is_end_badge;
                $updateAttributes['send_status_can_return_order'] = $status->can_return_order;
                $updateAttributes['send_status_title'] = $status->title;
                $updateAttributes['send_status_color_hex'] = $status->color_hex;
                $updateAttributes['send_status_changed_at'] = now();
                $updateAttributes['send_status_changed_by'] = Auth::user()?->id;
            }
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }

        $model = null;

        DB::transaction(function () use (&$model, $code, $updateAttributes, $silence) {
            $where = new WhereBuilder();
            $where->whereEqual('code', $code);

            $res = $this->repository->updateWhere($updateAttributes, $where->build());

            /**
             * @var User $model
             */
            $model = $this->repository->newWith('user')->findWhere($where->build());

            // send notification to user about send status changed
            if (!$silence && isset($status)) {
                OrderStatusChangedEvent::dispatch(
                    $model->user,
                    $model->code,
                    $model->send_status_title
                );
            }

            // return order products to stock if needed
            if (
                isset($status) &&
                $status['should_return_order_product'] &&
                !$model->is_product_returned_to_stock
            ) {
                $res = $res && $this->repository->returnOrderProductsToStock($model->id);
            }

            if (!$res) {
                DB::rollBack();
                $model = null;
            }
        });

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function updatePayment(int $orderId, array $attributes, bool $saveChanger = true): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['payment_status'])) {
            $updateAttributes['payment_status'] = $attributes['payment_status'];
            $updateAttributes['payment_status_changed_at'] = now();

            if ($saveChanger) {
                $updateAttributes['payment_status_changed_by'] = Auth::user()?->id;
            } else {
                $updateAttributes['payment_status_changed_by'] = null;
            }
        }

        $res = $this->repository->updatePayment($orderId, $updateAttributes);

        if (!$res) return null;

        return $this->repository->getPayment($orderId);
    }

    /**
     * @param User $user
     * @param array $orderInfo
     * @param Cart $cart
     * @return array<string, mixed>|null Returns an array as follows:
     *                                   <code>
     *                                       [
     *                                         'order_detail => Model,
     *                                         'payment_chunks_count => int,
     *                                       ]
     *                                   </code>
     *                                   or <strong>null</strong> on error
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws InvalidCartNameException
     * @throws NotFoundExceptionInterface
     */
    private function addOrderDetail(User $user, array $orderInfo, Cart $cart): ?array
    {
        /**
         * @var SendMethodRepositoryInterface $sendMethodRepository
         */
        $sendMethodRepository = app()->get(SendMethodRepositoryInterface::class);
        /**
         * @var PaymentMethodRepositoryInterface $paymentMethodRepository
         */
        $paymentMethodRepository = app()->get(PaymentMethodRepositoryInterface::class);
        /**
         * @var CouponRepositoryInterface $couponRepository
         */
        $couponRepository = app()->get(CouponRepositoryInterface::class);

        // -Check for payment method, it MUST be specified
        //  and if it's a bank gateway, it MUST be in supported gateways
        // -Also it must be checked if it's published in previous steps
        // -Don't forget about testing one
        $paymentMethod = $paymentMethodRepository->find($orderInfo['payment_method']);
        if (
            !$paymentMethod instanceof Model ||
            (
                $paymentMethod->type === PaymentTypesEnum::BANK_GATEWAY->value &&
                !in_array(
                    GatewaysEnum::tryFrom($paymentMethod->bank_gateway_type),
                    GatewaysEnum::supportedGateways()
                )
            ) ||
            (
                $paymentMethod->type === PaymentTypesEnum::TESTING->value &&
                !Auth::user()?->hasRole(RolesEnum::DEVELOPER->value)
            )
        ) {
            return null;
        }

        // check for order badge, it MUST be specified
        $where = new WhereBuilder();
        $where
            ->whereEqual('is_starting_badge', DatabaseEnum::DB_YES)
            ->whereEqual('is_published', DatabaseEnum::DB_YES);

        $badge = $this->badgeRepository->findWhere($where->build());
        if (!$badge instanceof Model) return null;

        // -Check for send method, it MUST be specified
        // -Also it must be checked if it's published in previous steps
        $sendMethod = $sendMethodRepository->find($orderInfo['send_method']);
        if (!$sendMethod instanceof Model) return null;

        //

        $provinceName = $this->provinceRepository->find($orderInfo['province'], ['name'])->name;
        $cityName = $this->cityRepository->find($orderInfo['city'], ['name'])->name;

        $coupon = null;
        if (!empty($orderInfo['coupon'])) {
            $coupon = $couponRepository->checkCoupon($orderInfo['coupon'], $user);
        }

        $shippingPrice = OrderHelper::shippingPriceCalculation(
            $orderInfo['province'],
            $orderInfo['city'],
            $cart,
            $sendMethod->id
        )['data'] ?? 0;

        $cartCalc = $cart->calculations();
        $totalDiscount = $cartCalc->totalPrice() - $cartCalc->totalDiscountedPrice();
        $finalPrice = $cartCalc->totalDiscountedPrice();
        $totalPrice = $cartCalc->totalPrice();

        if ($coupon instanceof Model) {
            if ($coupon->canApplyOn($finalPrice)) {
                $finalPrice -= $coupon->price;
            }
        }
        if ($shippingPrice > 0) {
            $finalPrice += $shippingPrice;
        }

        // generate a unique code for factors
        $code = get_nanoid(NanoIdInterface::ALPHABET_NUMBERS_READABLE . NanoIdInterface::ALPHABET_UPPERCASE_READABLE);
        $prefix = config('market.order.factor_prefix', '');

        // Update user info from order info if needed
        if (!$user->first_name) {
            $user->first_name = $orderInfo['first_name'];
        }
        if (!$user->last_name) {
            $user->last_name = $orderInfo['last_name'];
        }
        if (!$user->national_code) {
            $user->national_code = $orderInfo['national_code'];
        }
        if (!$user->first_name || !$user->last_name || !$user->national_code) {
            $user->save();
            $user->fresh();
        }

        // insert order info in details table
        $orderDetail = $this->repository->create([
            'user_id' => $user->id,
            'code' => $prefix . $code,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'national_code' => $user->national_code,
            'mobile' => $user->username,
            'province' => $provinceName,
            'city' => $cityName,
            'address' => $orderInfo['address'],
            'postal_code' => $orderInfo['postal_code'],
            'receiver_name' => $orderInfo['receiver_name'],
            'receiver_mobile' => $orderInfo['receiver_mobile'],
            'coupon_code' => $coupon instanceof Model ? $coupon->code : null,
            'coupon_title' => $coupon instanceof Model ? $coupon->title : null,
            'coupon_price' => $coupon instanceof Model ? $coupon->price : 0,
            'shipping_price' => $shippingPrice,
            'discount_price' => $totalDiscount,
            'final_price' => $finalPrice,
            'total_price' => $totalPrice,
            'send_method_title' => $sendMethod->title,
            'send_status_is_starting_badge' => $badge['is_starting_badge'],
            'send_status_is_end_badge' => $badge['is_end_badge'],
            'send_status_can_return_order' => $badge['can_return_order'],
            'send_status_code' => $badge['code'],
            'send_status_title' => $badge['title'],
            'send_status_color_hex' => $badge['color_hex'],
            'is_needed_factor' => to_boolean($orderInfo['is_needed_factor']),
            'ordered_at' => now(),
        ]);

        $orderPaymentRecordsCount = $this->addOrderPaymentRecords($orderDetail->id, $paymentMethod, $finalPrice);
        if (!$orderPaymentRecordsCount) return null;

        return [
            'order_detail' => $orderDetail,
            'payment_chunks_count' => $orderPaymentRecordsCount,
        ];
    }

    /**
     * @param int $orderId
     * @param Model $method
     * @param int $totalPrice
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function addOrderPaymentRecords(int $orderId, Model $method, int $totalPrice): int
    {
        /**
         * @var SettingServiceInterface $settingService
         */
        $settingService = app()->get(SettingServiceInterface::class);

        $dividePrice = $settingService->getSpecificSettings([SettingsEnum::DIVIDE_PAYMENT_PRICE])->first()['value'];
        $paymentChunks = [];

        if (!empty($dividePrice) && $dividePrice > 0) {
            $tmpTotalPrice = $totalPrice;
            while ($tmpTotalPrice > 0) {
                $mustPayPrice = min($tmpTotalPrice, $dividePrice);

                $paymentChunks[] = [
                    'key_id' => $orderId,
                    'must_pay_price' => $mustPayPrice,
                    'payment_method_id' => $method->id,
                    'payment_method_title' => $method->title,
                    'payment_method_type' => $method->type,
                    'payment_method_gateway_type' => $method->bank_gateway_type ?? null,
                    'payment_status' => PaymentStatusesEnum::PENDING->value,
                ];

                $tmpTotalPrice -= $dividePrice;
            }
        } else {
            $paymentChunks[] = [
                'key_id' => $orderId,
                'must_pay_price' => $totalPrice,
                'payment_method_id' => $method->id,
                'payment_method_title' => $method->title,
                'payment_method_type' => $method->type,
                'payment_method_gateway_type' => $method->bank_gateway_type ?? null,
                'payment_status' => PaymentStatusesEnum::PENDING->value,
            ];
        }

        $res = $this->repository->addPayments($paymentChunks);

        return $res ? count($paymentChunks) : 0;
    }

    /**
     * This method add order items to database and reduce item quantity from products stock
     *
     * @param int $orderId
     * @param Cart $cart
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function addOrderItems(int $orderId, Cart $cart): bool
    {
        /**
         * @var ProductRepositoryInterface $productRepository
         */
        $productRepository = app()->get(ProductRepositoryInterface::class);

        $cartCalc = $cart->calculations();
        $items = $cart->getContent();

        $where = new WhereBuilder();
        $where
            ->whereEqual('is_published', DatabaseEnum::DB_YES)
            ->whereIn('id', $items->map(fn($item) => $item->product_id)->toArray());
        $products = $productRepository->all(columns: ['id', 'unit_name'], where: $where->build());

        $refinedItems = $items->map(function ($item) use ($orderId, $cartCalc, $products) {
            $product = $products->firstWhere('id', $item->product_id);

            return [
                'order_key_id' => $orderId,
                'product_id' => $item->product_id,
                'product_code' => $item->code,
                'product_title' => $item->name,
                'color_name' => $item->color_name,
                'color_hex' => $item->color_hex,
                'size' => $item->size,
                'guarantee' => $item->guarantee,
                'weight' => $item->weight,
                'price' => $cartCalc->subtotalPriceFor($item->code),
                'discounted_price' => $cartCalc->subtotalDiscountedPriceFor($item->code),
                'unit_price' => $item->actual_price,
                'quantity' => $item->qty,
                'unit_name' => $product['unit_name'] ?? '',
                'has_separate_shipment' => $item->has_separate_shipment,
            ];
        });

        return $this->repository->addItemsToOrder($refinedItems->toArray()) &&
            $this->reduceOrderItemsFromStock($refinedItems->toArray());
    }

    /**
     * @param array $items
     * @return bool
     */
    private function reduceOrderItemsFromStock(array $items): bool
    {
        return $this->repository->reduceOrderProductsFromStock($items);
    }

    /**
     * @param int $orderId
     * @param int $paymentChunksCount
     * @return bool
     */
    private function reserveOrder(int $orderId, int $paymentChunksCount): bool
    {
        $time = OrderHelper::getReservationTime($paymentChunksCount);
        $expireSeconds = $time;
        $model = $this->repository->createReserveOrder([
            'user_id' => Auth::user()?->id,
            'order_key_id' => $orderId,
            'expires_at' => now()->addSeconds($expireSeconds),
        ]);

        return $model instanceof Model;
    }
}
