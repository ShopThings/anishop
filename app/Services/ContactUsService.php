<?php

namespace App\Services;

use App\Repositories\Contracts\ContactUsRepositoryInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContactUsService extends Service implements ContactUsServiceInterface
{
    public function __construct(
        protected ContactUsRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getContacts(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('contact_us');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'title',
                'name',
                'mobile',
                'description',
            ], $search);
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
    public function getUserContacts($userId, Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('contact_us');
        $where
            ->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
                $query->orWhereLike([
                    'title',
                    'name',
                    'mobile',
                    'description',
                ], $search);
            })
            ->whereEqual('user_id', $userId);

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
    public function getContactsCount($userId): int
    {
        $where = new WhereBuilder('contact_us');
        $where->whereEqual('user_id', $userId);

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

        return $this->repository->create($attrs);
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

    /**
     * @inheritDoc
     */
    public function deleteUserContactById($id): bool
    {
        return (bool)$this->repository->delete($id, false);
    }
}
