<?php

namespace App\Services;

use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Services\Contracts\NewsletterServiceInterface;
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
    public function getMembers(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('newsletters');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike('mobile', $search);
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
