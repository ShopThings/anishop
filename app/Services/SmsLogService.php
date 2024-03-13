<?php

namespace App\Services;

use App\Enums\SMS\SMSSenderTypesEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Repositories\Contracts\SmsLogRepositoryInterface;
use App\Services\Contracts\SmsLogServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class SmsLogService extends Service implements SmsLogServiceInterface
{
    public function __construct(
        protected SmsLogRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getLogs(Filter $filter): Collection|LengthAwarePaginator
    {
        $searchText = $filter->getSearchText();

        $where = new WhereBuilder('sms_logs');
        $where
            ->when($searchText, function (WhereBuilderInterface $query, $search) {
                $query->orWhereLike([
                    'panel_number',
                    'panel_name',
                    'receiver_numbers',
                ], $search);
            })
            ->when(
                SMSTypesEnum::getSimilarValuesFromString($searchText),
                function (WhereBuilderInterface $q, array $types) {
                    $q->whereIn('type', $types);
                }
            )
            ->when(
                SMSSenderTypesEnum::getSimilarValuesFromString($searchText),
                function (WhereBuilderInterface $q, array $types) {
                    $q->whereIn('sender', $types);
                }
            );

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
            'receiver_numbers' => $attributes['receiver_numbers'],
            'panel_number' => $attributes['panel_number'],
            'panel_name' => $attributes['panel_name'],
            'body' => $attributes['body'],
            'type' => $attributes['type'],
            'sender' => $attributes['sender'],
        ];

        return $this->repository->create($attrs);
    }
}
