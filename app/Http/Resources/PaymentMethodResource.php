<?php

namespace App\Http\Resources;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image_id' => $this->image_id,
            'image' => $this->whenLoaded('image'),
            'type' => [
                'text' => PaymentTypesEnum::getTranslations($this->type),
                'value' => $this->type,
            ],
            'bank_gateway_type' => [
                'text' => GatewaysEnum::getTranslations($this->bank_gateway_type),
                'value' => $this->bank_gateway_type,
            ],
            'options' => $this->options,
            'is_published' => $this->is_published,
            'is_deletable' => $this->is_deletable,
            'created_by' => $this->when($this->created_by, $this->creator()),
            'updated_by' => $this->when($this->updated_by, $this->updater()),
            'deleted_by' => $this->when($this->deleted_by, $this->deleter()),
            'created_at' => $this->created_at
                ? verta($this->created_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
                : null,
            'updated_at' => $this->when(
                $this->updated_at,
                verta($this->updated_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
            'deleted_at' => $this->when(
                $this->deleted_at,
                verta($this->deleted_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)
            ),
        ];
    }
}
