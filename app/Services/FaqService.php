<?php

namespace App\Services;

use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Services\Contracts\FaqServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use App\Support\WhereBuilder\WhereBuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use function App\Support\Helper\to_boolean;

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
    public function getFaqs(
        ?string $searchText = null,
        int     $limit = 15,
        int     $page = 1,
        array   $order = ['column' => 'id', 'sort' => 'desc']
    ): Collection|LengthAwarePaginator
    {
        $where = new WhereBuilder('faqs');
        $where->when($searchText, function (WhereBuilderInterface $query, $search) {
            $query->orWhereLike([
                'question',
                'keywords',
            ], $search);
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
            'question' => $attributes['question'],
            'answer' => $attributes['answer'],
            'keywords' => $attributes['keywords'],
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
