<?php

namespace App\Http\Resources;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Http\Resources\Showing\ImageShowInfoResource;
use App\Http\Resources\Showing\UserShowResource;
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
            'image' => $this->whenLoaded('image', function () {
                return $this->image?->id
                    ? new ImageShowInfoResource($this->image)
                    : null;
            }),
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
            'created_by' => $this->created_by ? new UserShowResource($this->creator) : null,
            'updated_by' => $this->when($this->updated_by, new UserShowResource($this->updater)),
            'deleted_by' => $this->when($this->deleted_by, new UserShowResource($this->deleter)),
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
