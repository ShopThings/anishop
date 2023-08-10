<?php

namespace App\Models;

use App\Enums\Menus\MenuPlacesEnum;
use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuPlace extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'place_in' => MenuPlacesEnum::class,
        'is_published' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
