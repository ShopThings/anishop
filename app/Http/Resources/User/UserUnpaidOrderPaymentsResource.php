<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Home\OrderPaymentResource;
use App\Support\Helper\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserUnpaidOrderPaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'chunks' => OrderPaymentResource::collection($this->orders),
            'remained_pay_time' => OrderHelper::calculateRemainedPayTime($this->resource),
        ];
    }
}
