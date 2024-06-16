<?php

namespace App\Support\Traits;

use App\Enums\Gates\PermissionsEnum;
use App\Exceptions\NotDeletableException;
use App\Models\User;
use App\Support\Gate\PermissionHelper;

trait PolicyTrait
{
    /**
     * @var array|string[]
     */
    protected array $fn = [
        'viewAny',
        'view',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
        'batchDelete',
    ];

    /**
     * @var array|string[]
     */
    protected array $only;

    /**
     * @var array|string[]
     */
    protected array $except;

    /**
     * @var bool
     */
    protected bool $checkCreator = true;

    /**
     * @var bool
     */
    protected bool $checkIsDeletable = true;

    public function __construct()
    {
        parent::__construct();

        if (count($this->except)) $this->fn = array_diff($this->fn, $this->except);
        if (count($this->only)) $this->fn = $this->only;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return bool|mixed|null
     * @throws NotDeletableException
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists(static::class, $name)) {
            return call_user_func_array($name, $arguments);
        }

        if (!in_array($name, $this->fn)) return false;

        [$user, $model] = $arguments;
        if (!$user instanceof User) return null;

        if ($name === 'viewAny') { // Determine whether the user can view any models.
            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::READ,
                    $this->permissionPlace)
            );
        } elseif ($name === 'view') { // Determine whether the user can view the model.
            if ($this->checkCreator && $user->id === $model->creator?->id) return true;

            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::READ,
                    $this->permissionPlace)
            );
        } elseif ($name === 'create') { // Determine whether the user can create models.
            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::CREATE,
                    $this->permissionPlace)
            );
        } elseif ($name === 'update') { // Determine whether the user can update the model.
            if ($this->checkCreator && $user->id === $model->creator?->id) return true;

            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::UPDATE,
                    $this->permissionPlace)
            );
        } elseif ($name === 'delete') { // Determine whether the user can delete the model.
            if ($this->checkIsDeletable && !$model->is_deletable) {
                throw new NotDeletableException();
            }
            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::DELETE,
                    $this->permissionPlace)
            );
        } elseif ($name === 'restore') { // Determine whether the user can restore the model.
            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::UPDATE,
                    $this->permissionPlace)
            );
        } elseif ($name === 'forceDelete') { // Determine whether the user can permanently delete the model.
            if ($user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::PERMANENT_DELETE,
                    $this->permissionPlace)
            )) {
                return true;
            } else {
                if ($model instanceof $this->modelClass) {
                    if ($user->id === $model->creator?->id)
                        return true;
                } else {
                    $tmp = $model->filter(function ($item) use ($user) {
                        return isset($item->creator->id) && $user->id !== $item->creator->id;
                    });

                    if (!$tmp->count())
                        return true;
                }
                return false;
            }
        } elseif ($name === 'batchDelete') { // Determine whether the user can batch delete.
            return $user->hasPermissionTo(
                PermissionHelper::permission(
                    PermissionsEnum::DELETE,
                    $this->permissionPlace)
            );
        }

        return false;
    }
}
