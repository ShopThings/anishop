<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Events\ContactAddedEvent;
use App\Repositories\Contracts\ContactUsRepositoryInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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

        return $this->repository
            ->newWith(['answeredBy', 'statusChanger', 'creator', 'deleter'])
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
    public function getContactsCount(): int
    {
        return $this->repository->count();
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
            ->whereEqual('created_by', $userId);

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
    public function getNotSeenContactsCount(): int
    {
        $where = new WhereBuilder('contact_us');
        $where->whereEqual('is_seen', DatabaseEnum::DB_NO);

        return $this->repository->count($where->build());
    }

    /**
     * @inheritDoc
     */
    public function getUserContactsCount($userId): int
    {
        $where = new WhereBuilder('contact_us');
        $where->whereEqual('created_by', $userId);

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
            'message' => $attributes['description'],
        ];

        $model = $this->repository->create($attrs);

        if (is_null($model)) {
            return null;
        }

        $user = Auth::user();
        if (!is_null($user)) {
            ContactAddedEvent::dispatch($user);
        }
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes['answer'])) {
            $updateAttributes['answer'] = $attributes['answer'];
            $updateAttributes['answered_at'] = now();
            $updateAttributes['answered_by'] = Auth::user()?->id;
        }
        if (isset($attributes['is_seen'])) {
            $updateAttributes['is_seen'] = to_boolean($attributes['is_seen']);
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
