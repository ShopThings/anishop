<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Repositories\Contracts\OrderBadgeRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    public function getOrders(?int $userId = null, Filter $filter = null): Collection|LengthAwarePaginator
    {
        return $this->repository->getOrdersSearchFilterPaginated(userId: $userId, filter: $filter);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
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
                $updateAttributes['send_status_title'] = $status->title;
                $updateAttributes['send_status_color_hex'] = $status->color_hex;
                $updateAttributes['send_status_changed_at'] = now();
                $updateAttributes['send_status_changed_by'] = Auth::user()?->id;
            }
        }
        if (isset($attributes['description'])) {
            $updateAttributes['description'] = $attributes['description'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function updatePayment(int $orderId, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['payment_status'])) {
            $updateAttributes['payment_status'] = $attributes['payment_status'];
            $updateAttributes['payment_status_changed_at'] = now();
            $updateAttributes['payment_status_changed_by'] = Auth::user()?->id;
        }

        $res = $this->repository->updatePayment($orderId, $updateAttributes);

        if (!$res) return null;

        return $this->repository->getPayment($orderId);
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
}
