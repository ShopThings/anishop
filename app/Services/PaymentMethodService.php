<?php

namespace App\Services;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Repositories\Contracts\PaymentMethodRepositoryInterface;
use App\Services\Contracts\PaymentMethodServiceInterface;
use App\Support\Model\CodeGeneratorHelper;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class PaymentMethodService extends Service implements PaymentMethodServiceInterface
{
    public function __construct(
        protected PaymentMethodRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getMethods(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('payment_methods');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query
                ->when(PaymentTypesEnum::getSimilarValuesFromString($search), function (WhereBuilderInterface $q, array $items) {
                    $q->orWhereIn('type', $items);
                })
                ->when(GatewaysEnum::getSimilarValuesFromString($search), function (WhereBuilderInterface $q, array $items) {
                    $q->orWhereIn('bank_gateway_type', $items);
                })
                ->orWhereLike('title', $search);
        });

        return $this->repository->paginate(
            where: $where->build(), page: $page, limit: $limit, order: $this->convertOrdersColumnToArray($order)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'title' => $attributes['title'],
            'image_id' => $attributes['image'],
            'type' => $attributes['type'],
            'bank_gateway_type' => $attributes['bank_gateway_type'],
            'options' => $attributes['options'],
            'is_published' => to_boolean($attributes['is_published']),
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['title'])) {
            $updateAttributes['title'] = $attributes['title'];
        }
        if (isset($attributes['image'])) {
            $updateAttributes['image_id'] = $attributes['image'];
        }
        if (isset($attributes['type'])) {
            $updateAttributes['type'] = $attributes['type'];
        }
        if (isset($attributes['bank_gateway_type'])) {
            $updateAttributes['bank_gateway_type'] = $attributes['bank_gateway_type'];
        }
        if (isset($attributes['options'])) {
            $updateAttributes['options'] = $attributes['options'];
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }
        if (isset($attributes['is_deletable'])) {
            $updateAttributes['is_deletable'] = to_boolean($attributes['is_deletable']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
