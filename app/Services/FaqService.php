<?php

namespace App\Services;

use App\Enums\DatabaseEnum;
use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Services\Contracts\FaqServiceInterface;
use App\Support\Filter;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class FaqService extends Service implements FaqServiceInterface
{
    public function __construct(
        protected FaqRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getFaqs(Filter $filter): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('faqs');
        $where->when($filter->getSearchText(), function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'question',
                'keywords',
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
    public function getHomeFaqs(Filter $filter): Collection
    {
        $where = new WhereBuilder('faqs');
        $where->whereEqual('is_published', DatabaseEnum::DB_YES);

        return $this->repository->all(
            columns: ['id', 'question', 'answer', 'keywords'],
            order: $filter->getOrder()
        );
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            'question' => $attributes['question'],
            'answer' => $attributes['answer'],
            'keywords' => $attributes['keywords'] ?? [],
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

        if (isset($attributes['question'])) {
            $updateAttributes['question'] = $attributes['question'];
        }
        if (isset($attributes['answer'])) {
            $updateAttributes['answer'] = $attributes['answer'];
        }
        if (isset($attributes['keywords'])) {
            $updateAttributes['keywords'] = $attributes['keywords'];
        }
        if (isset($attributes['is_published'])) {
            $updateAttributes['is_published'] = to_boolean($attributes['is_published']);
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
