<?php

namespace App\Services;

use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Services\Contracts\CouponServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

class CouponService extends Service implements CouponServiceInterface
{
    public function __construct(
        protected CouponRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getCoupons(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('coupons');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike(['title', 'code'], $search);
        });

        return $this->repository->paginate(
            where: $where->build(),
            limit: $filter->getLimit(),
            page: $filter->getPage(),
            order: $filter->getOrder()
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'title' => $attributes['title'],
            'code' => $attributes['code'],
            'price' => $attributes['price'],
            'apply_min_price' => $attributes['apply_min_price'] ?? null,
            'apply_max_price' => $attributes['apply_max_price'] ?? null,
            'start_at' => isset($attributes['start_at'])
                ? Verta::createFromFormat($attributes['start_at'], 'Y-m-d H:i:s')
                : null,
            'end_at' => isset($attributes['end_at'])
                ? Verta::createFromFormat($attributes['end_at'], 'Y-m-d H:i:s')
                : null,
            'use_count' => $attributes['use_count'],
            'reusable_after' => $attributes['reusable_after'],
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
        if (isset($attributes['code'])) {
            $updateAttributes['code'] = $attributes['code'];
        }
        if (isset($attributes['price'])) {
            $updateAttributes['price'] = $attributes['price'];
        }
        if (isset($attributes['apply_min_price'])) {
            $updateAttributes['apply_min_price'] = $attributes['apply_min_price'];
        }
        if (isset($attributes['apply_max_price'])) {
            $updateAttributes['apply_max_price'] = $attributes['apply_max_price'];
        }
        if (isset($attributes['start_at'])) {
            $updateAttributes['start_at'] = Verta::createFromFormat($attributes['start_at'], 'Y-m-d H:i:s');
        }
        if (isset($attributes['end_at'])) {
            $updateAttributes['end_at'] = Verta::createFromFormat($attributes['end_at'], 'Y-m-d H:i:s');
        }
        if (isset($attributes['use_count'])) {
            $updateAttributes['use_count'] = $attributes['use_count'];
        }
        if (isset($attributes['reusable_after'])) {
            $updateAttributes['reusable_after'] = $attributes['reusable_after'];
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
