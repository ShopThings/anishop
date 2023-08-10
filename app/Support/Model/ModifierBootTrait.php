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
        if ($this->hasCreatedBy)
            $model->created_by = Auth::user()?->id;
    }

    /**
     * @param $model
     * @return void
     */
    protected function addUpdatedBy($model): void
    {
        if ($this->hasUpdatedBy)
            $model->updated_by = Auth::user()?->id;
    }

    protected function addCreatedAt($model): void
    {
        if (!$this->timestamps) {
            if (array_key_exists(self::CREATED_AT, $this->getCasts()))
                $model->{self::CREATED_AT} = now();
        }
    }

    protected function addUpdatedAt($model): void
    {
        if (!$this->timestamps) {
            if (array_key_exists(self::UPDATED_AT, $this->getCasts()))
                $model->{self::UPDATED_AT} = now();
        }
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $this->addCreatedAt($model);
            $this->addCreatedBy($model);
            $this->addUpdatedBy($model);
        });
        static::updating(function ($model) {
            $this->addUpdatedAt($model);
            $this->addUpdatedBy($model);
        });
    }
}
