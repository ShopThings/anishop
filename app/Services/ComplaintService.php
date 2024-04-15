<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Events\ComplaintAddedEvent;
use App\Repositories\Contracts\ComplaintRepositoryInterface;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ComplaintService extends Service implements ComplaintServiceInterface
{
    public function __construct(
        protected ComplaintRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getComplaints(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('complaints');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'title',
                'name',
                'mobile',
                'description',
            ], $search);
        });

        return $this->repository
            ->newWith(['statusChanger', 'creator', 'deleter'])
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
    public function getNotSeenComplaintsCount(): int
    {
        $where = new WhereBuilder('complaints');
        $where->whereEqual('is_seen', DatabaseEnum::DB_NO);

        return $this->repository->count($where->build());
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'title' => $attributes['title'],
            'name' => $attributes['name'],
            'mobile' => $attributes['mobile'],
            'description' => $attributes['description'],
        ];

        $model = $this->repository->create($attrs);

        if (is_null($model)) {
            return null;
        }

        $user = Auth::user();
        if (!is_null($user)) {
            ComplaintAddedEvent::dispatch($user);
        }
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['is_seen'])) {
            $updateAttributes['is_seen'] = $attributes['is_seen'];
            $updateAttributes['changed_status_at'] = now();
            $updateAttributes['changed_status_by'] = Auth::user()?->id;
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
