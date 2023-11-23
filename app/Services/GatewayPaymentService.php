<?php

namespace App\Services;

use App\Repositories\Contracts\GatewayPaymentRepositoryInterface;
use App\Services\Contracts\GatewayPaymentServiceInterface;
use App\Support\Service;
use Illuminate\Database\Eloquent\Model;

class GatewayPaymentService extends Service implements GatewayPaymentServiceInterface
{
    public function __construct(
        protected GatewayPaymentRepositoryInterface $repository
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes): ?Model
    {
        $attrs = [
            '' => $attributes[''],
        ];

        return $this->repository->create($attrs);
    }

    /**
     * @inheritDoc
     */
    public function updateById($id, array $attributes): ?Model
    {
        $updateAttributes = [];

        if (isset($attributes[''])) {
            $updateAttributes[''] = $attributes[''];
        }

        $res = $this->repository->update($id, $updateAttributes);

        if (!$res) return null;

        return $this->getById($id);
    }
}
