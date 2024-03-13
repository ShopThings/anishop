<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait SelfHealingRouteTrait
{
    /**
     * @return string
     */
    public function getHealingRoute(): string
    {
        return '/';
    }

    /**
     * @return string|null
     */
    protected function getHealingRouteKeyName(): ?string
    {
        return $this->slug;
    }

    public function getRouteKey()
    {
        $healingKeyName = $this->getHealingRouteKeyName();

        // "empty" is a good choice for checking null or empty string
        if (empty($healingKeyName)) return parent::getRouteKey();

        return $healingKeyName . '-' . $this->id;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $id = last(explode('-', $value));
        $model = parent::resolveRouteBinding($id, $field);

        if (!$model || $model->getRouteKey() === $model) {
            return $model;
        }

        throw new HttpResponseException(
            redirect()->route($this->getHealingRoute() ?: '/', $model)
        );
    }
}
