<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface ServiceInterface extends VersionInterface
{
    /**
     * @param $id
     * @return Collection|Model|null
     */
    public function getById($id): Collection|Model|null;

    /**
     * @param $id
     * @param bool $permanent
     * @return bool
     */
    public function deleteById($id, bool $permanent = false): bool;

    /**
     * @param array $ids
     * @param bool $permanent
     * @return bool
     */
    public function batchDeleteByIds(array $ids, bool $permanent = false): bool;

    /**
     * @param array $attributes
     * @return Model|null
     */
    public function create(array $attributes): ?Model;

    /**
     * @param $id
     * @param array $attributes
     * @return Model|null
     */
    public function updateById($id, array $attributes): ?Model;
}
