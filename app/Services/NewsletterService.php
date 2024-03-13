<?php

namespace App\Services;

use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class NewsletterService extends Service implements NewsletterServiceInterface
{
    public function __construct(
        protected NewsletterRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getMembers(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('newsletters');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('mobile', $search);
        });

        return $this->repository
            ->newWith(['creator', 'updater', 'deleter'])
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
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'mobile' => $attributes['mobile'],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['mobile'])) {
            $updateAttributes['mobile'] = $attributes['mobile'];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
