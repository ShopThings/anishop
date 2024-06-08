<?php

namespace App\Support\Traits;

use App\Services\Contracts\FileServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait ImageFieldTrait
{
    /**
     * @param $imageAttribute
     * @return int|null
     */
    protected function getImageId($imageAttribute): ?int
    {
        if (is_null($imageAttribute)) return null;

        $info = pathinfo($imageAttribute);
        if (empty($info['extension'])) return null;

        /**
         * @var FileServiceInterface $service
         */
        try {
            $service = app()->get(FileServiceInterface::class);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            return null;
        }

        $model = $service->find($imageAttribute);

        return $model instanceof Model ? $model->id : null;
    }

    /**
     * @param int|null $imageId
     * @return Model|null
     */
    protected function getImageFromId(?int $imageId): ?Model
    {
        if (is_null($imageId)) return null;

        /**
         * @var FileServiceInterface $service
         */
        try {
            $service = app()->get(FileServiceInterface::class);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            return null;
        }

        return $service->find($imageId, true);
    }
}
