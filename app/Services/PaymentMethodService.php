<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Repositories\Contracts\PaymentMethodRepositoryInterface;
use App\Services\Contracts\PaymentMethodServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\Traits\ImageFieldTrait;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodService extends Service implements PaymentMethodServiceInterface
{
    use ImageFieldTrait;

    public function __construct(
        protected PaymentMethodRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getMethods(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('payment_methods');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query
                ->when(PaymentTypesEnum::getSimilarValuesFromString($search), function (WhereBuilderInterface $q, array $items) {
                    $q->orWhereIn('type', $items);
                })
                ->when(GatewaysEnum::getSimilarValuesFromString($search), function (WhereBuilderInterface $q, array $items) {
                    $q->orWhereIn('bank_gateway_type', $items);
                })
                ->orWhereLike('title', $search);
        });

        return $this->repository
            ->newWith(['image', 'creator', 'updater', 'deleter'])
            ->paginate(
                where: $where->build(),
                limit: $filter->getLimit(),
                page: $filter->getPage(),
                order: $filter->getOrder()
            );
    }

    /**
     * @inheritDoc
     */
    public function getHomeMethods(): Collection
    {
        $where = new WhereBuilder('payment_methods');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository
            ->newWith('image')
            ->all(where: $where->build(), order: ['id' => 'asc']);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attributes['image'] = $this->getImageId($attributes['image'] ?? null);

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
            $attributes['image'] = $this->getImageId($attributes['image'] ?? null);
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
