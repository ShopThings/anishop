<?php

namespace App\Support\Model;

use Illuminate\Support\Facades\Auth;

trait ModifierBootTrait
{
    /**
     * @var bool
     */
    protected $hasCreatedBy = true;

    /**
     * @var bool
     */
    protected $hasUpdatedBy = true;

    /**
     * @param $model
     * @return void
     */
    protected function addCreatedBy($model): void
    {
        if ($this->hasCreatedBy && (!isset($model->created_by) && !is_null($model->created_by))) {
            $model->created_by = Auth::user()?->id;
        }
    }

    /**
     * @param $model
     * @return void
     */
    protected function addUpdatedBy($model): void
    {
        if ($this->hasUpdatedBy && (!isset($model->updated_by) && !is_null($model->updated_by))) {
            $model->updated_by = Auth::user()?->id;
        }
    }

    /**
     * @param $model
     * @return void
     */
    protected function addCreatedAt($model): void
    {
        if (!$this->timestamps) {
            if (array_key_exists(self::CREATED_AT, $this->getCasts())) {
                $model->{self::CREATED_AT} = now();
            }
        }
    }

    /**
     * @param $model
     * @return void
     */
    protected function addUpdatedAt($model): void
    {
        if (!$this->timestamps) {
            if (array_key_exists(self::UPDATED_AT, $this->getCasts())) {
                $model->{self::UPDATED_AT} = now();
            }
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->addCreatedAt($model);
            $model->addCreatedBy($model);
        });

        static::updating(function ($model) {
            $model->addUpdatedAt($model);
            $model->addUpdatedBy($model);
        });
    }
}
